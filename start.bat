@echo off
REM ============================================================
REM  Inventar — pokretač Laravel servera
REM  Pronalazi PHP iz XAMPP / Laragon / Herd / sistemskog PATH-a
REM  i pokreće php artisan serve.
REM ============================================================

setlocal

REM 1. Pokušaj naći php.exe — redom: PATH, XAMPP, Laragon, Herd
set "PHP_EXE="

where php >nul 2>&1
if %ERRORLEVEL%==0 (
    set "PHP_EXE=php"
    goto :have_php
)

if exist "C:\xampp\php\php.exe" (
    set "PHP_EXE=C:\xampp\php\php.exe"
    set "PATH=C:\xampp\php;%PATH%"
    goto :have_php
)

if exist "C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe" (
    set "PHP_EXE=C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe"
    goto :have_php
)

REM Probaj naći bilo koju PHP verziju u Laragonu
for /d %%D in ("C:\laragon\bin\php\php-*") do (
    if exist "%%D\php.exe" (
        set "PHP_EXE=%%D\php.exe"
        set "PATH=%%D;%PATH%"
        goto :have_php
    )
)

if exist "%LOCALAPPDATA%\Herd\bin\php.bat" (
    set "PHP_EXE=%LOCALAPPDATA%\Herd\bin\php.bat"
    goto :have_php
)

echo.
echo ============================================================
echo  GRESKA: Ne mogu pronaci php.exe.
echo ============================================================
echo.
echo  Provjeri da imas instaliran jedan od ovih:
echo    - XAMPP    (C:\xampp\php\php.exe)
echo    - Laragon  (C:\laragon\bin\php\...)
echo    - Laravel Herd
echo.
echo  Ako PHP imas, ali drugdje, dodaj njegovu mapu u PATH ili
echo  zovi nastavnika.
echo.
pause
exit /b 1

:have_php
echo.
echo PHP pronaden: %PHP_EXE%
"%PHP_EXE%" -v
echo.

REM 2. Provjeri postoji li .env, ako ne — napravi ga
if not exist .env (
    echo .env ne postoji, kopiram .env.example...
    copy .env.example .env >nul
    "%PHP_EXE%" artisan key:generate
    echo.
)

REM 3. Pokreni migracije + seed (samo ako tablica ne postoji ili je prazna)
REM    Marker datoteka u storage/app spreCava ponovno seedanje.
if not exist storage\app\seeded.flag (
    echo Pokrecem migracije i punim bazu...
    "%PHP_EXE%" artisan migrate --seed --force
    echo > storage\app\seeded.flag
) else (
    echo Pokrecem migracije...
    "%PHP_EXE%" artisan migrate --force
)
echo.

REM 4. Pokreni server
echo ============================================================
echo  Otvori u browseru: http://localhost:8000/api/categories
echo  Za prekid servera pritisni Ctrl+C u ovom prozoru.
echo ============================================================
echo.
"%PHP_EXE%" artisan serve

endlocal

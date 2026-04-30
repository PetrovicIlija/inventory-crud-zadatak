# Inventar — Laravel CRUD Zadatak

Kostur Laravel projekta za vježbu implementacije CRUD operacija na backendu.

---

## Preduvjeti (Windows)

Prije postavljanja projekta instaliraj sljedeće alate. Nakon svake instalacije otvori **novi** Command Prompt ili PowerShell prozor da bi se nove naredbe prepoznale.

### 1. Laragon (PHP + MySQL + Composer u jednom)

Najlakši način za Windows — dolazi s PHP-om, MySQL-om, Composerom i Apacheom.

- Preuzmi: https://laragon.org/download/ (verzija **Full**)
- Instaliraj i pokreni Laragon, klikni **Start All**.
- Provjeri da je instalirana verzija PHP-a **8.3 ili novija**. Ako nije, u Laragonu: desni klik → **PHP** → **Version** → preuzmi noviju verziju.

> Alternativa: zasebno instalirati [PHP 8.3+](https://windows.php.net/download/), [Composer](https://getcomposer.org/Composer-Setup.exe) i [MySQL](https://dev.mysql.com/downloads/installer/), ali Laragon je puno jednostavniji.

### 2. Node.js 20+ (s npm-om)

- Preuzmi LTS verziju: https://nodejs.org/
- Instaliraj sa zadanim postavkama (uključuje npm).

### 3. Git

- Preuzmi: https://git-scm.com/download/win
- Instaliraj sa zadanim postavkama.

### 4. Sublime Text + paketi

- Sublime Text: https://www.sublimetext.com/download
- Instaliraj **Package Control** (Ctrl+Shift+P → "Install Package Control").
- Preporučeni paketi (Ctrl+Shift+P → "Package Control: Install Package"):
  - `PHP Companion` — autocomplete i navigacija po PHP klasama
  - `Laravel Blade Highlighter` — bojanje Blade templatea
  - `SublimeLinter` + `SublimeLinter-php` — provjera PHP sintakse
  - `EditorConfig` — poštivanje `.editorconfig` postavki

### 5. Provjera instalacije

Otvori **Command Prompt** (cmd) i provjeri verzije:

```cmd
php -v
composer -V
mysql --version
node -v
npm -v
git --version
```

Ako neka od naredbi ne radi, najčešće je problem u **PATH** varijabli — javi nastavniku.

---

## Postavljanje projekta

### 1. Kloniraj repozitorij

```bash
git clone <url-repozitorija>
cd inventory-crud-zadatak
```

### 2. Instaliraj PHP ovisnosti

```bash
composer install
```

### 3. Postavi konfiguraciju

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Postavi bazu podataka

Otvori `.env` i provjeri podatke za MySQL:

```
DB_DATABASE=inventar
DB_USERNAME=root
DB_PASSWORD=          # tvoja lozinka (ili ostavi prazno)
```

Kreiraj bazu u MySQL-u:

```sql
CREATE DATABASE inventar;
```

### 5. Pokreni migracije i punjenje baze

```bash
php artisan migrate --seed
```

### 6. Pokreni razvojni server

```bash
php artisan serve
```

Otvori `http://localhost:8000` u pregledniku.

---

## Zadatak

Sve je gotovo osim `ArticleController.php`.

Otvori datoteku:

```
app/Http/Controllers/ArticleController.php
```

Implementiraj 4 metode prema komentarima unutar datoteke.

**Za pomoć pogledaj gotov primjer:**

```
app/Http/Controllers/CategoryController.php
```

---

## Struktura projekta (relevantni dijelovi)

```
app/
  Http/
    Controllers/
      CategoryController.php   ← gotov primjer
      ArticleController.php    ← TVOJ ZADATAK
    Requests/
      ArticleRequest.php       ← validacija (gotovo)
  Models/
    Category.php
    Article.php
  Http/Resources/
    ArticleResource.php
    CategoryResource.php
database/
  migrations/
routes/
  api.php
```

---

## API endpointi

| Metoda | URL | Opis |
|--------|-----|------|
| GET | `/api/articles` | Lista svih artikala |
| POST | `/api/articles` | Kreiraj novi artikl |
| PUT | `/api/articles/{id}` | Ažuriraj artikl |
| DELETE | `/api/articles/{id}` | Obriši artikl |
| GET | `/api/categories` | Lista kategorija |

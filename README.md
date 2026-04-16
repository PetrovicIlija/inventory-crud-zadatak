# Inventar — Laravel CRUD Zadatak

Kostur Laravel projekta za vježbu implementacije CRUD operacija na backendu.

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

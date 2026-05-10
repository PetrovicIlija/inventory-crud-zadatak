# Inventar — Laravel CRUD Zadatak

Kostur Laravel projekta za vježbu implementacije CRUD operacija.

---

## Pokretanje u GitHub Codespaces (preporučeno)

Codespaces je cloud razvojno okruženje — sve radi u browseru, **ne treba ništa instalirati**.

### 1. Kreiraj Codespace

1. Otvori repozitorij na GitHubu.
2. Klikni zelenu **`<> Code`** tipku → tab **Codespaces** → **Create codespace on main**.
3. Pričekaj 1–2 minute dok se okruženje ne postavi (composer install, migracije, seed — sve ide automatski).

### 2. Pokreni server

U terminalu unutar Codespacesa:

```bash
php artisan serve --host=0.0.0.0
```

Codespaces će automatski otvoriti **Preview** tab. Otvori dodatak `/api/articles` na URL-u i trebao bi vidjeti JSON s artiklima.

### 3. Što ćeš vidjeti

- **Frontend (Blade UI):** korijenski URL `/` — tablica artikala, modal za dodavanje/uređivanje.
- **API endpointi:** `/api/articles`, `/api/categories`.

> Napomena: prvi put kad Codespaces forwarda port 8000, postavi ga na **Public** (desni klik na port → Port Visibility → Public) ako želiš dijeliti link s nekim.

---

## Lokalno (alternativa, npr. doma na Macu/Linuxu)

Trebaš PHP 8.3+ i Composer.

```bash
composer install
cp .env.codespaces .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Otvori `http://localhost:8000`.

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
.devcontainer/
  devcontainer.json          ← Codespaces konfiguracija
  setup.sh                   ← auto-setup skripta

app/
  Http/
    Controllers/
      CategoryController.php   ← gotov primjer
      ArticleController.php    ← TVOJ ZADATAK
    Requests/
      ArticleRequest.php       ← validacija (gotovo)
    Resources/
      ArticleResource.php
      CategoryResource.php
  Models/
    Category.php
    Article.php
database/
  migrations/
  seeders/
routes/
  api.php
  web.php
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

---

## Predaja

Svaki commit u tvom Codespaces-u + push na GitHub = predaja. Detalje vidi u radnom listu / na Loomenu.

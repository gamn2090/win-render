# Wedding Insiders — local setup

**Laravel** (PHP) application with assets via **Vite**. Requires a PHP environment, Composer, Node, and a MySQL database configured in `.env`.

**Reference environment:** the project is currently developed and run on **Linux**. Linux instructions take priority; an equivalent section for **Windows** is included at the end of this document.

---

## Requirements

- PHP **8.1+** with common Laravel extensions (`openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`; for MySQL: `pdo_mysql`)
- **Composer** (must resolve dependencies with the same PHP you use for Artisan)
- **Node.js** LTS and **npm**
- **MySQL** accessible according to `DB_*` in `.env`

Reference documentation: [Laravel](https://laravel.com/docs), [Composer](https://getcomposer.org/), [Node.js](https://nodejs.org/).

---

## Environment variables (`.env`)

The `.env.example` file only defines the **template**. For a functional setup with the product integrations, you need the **variables provided by the client** (or the project owner). They are not included in the repository.

Ask the client for a complete `.env` or, at minimum, the values for the groups you will use:

| Group | Variables (reference in `.env.example`) | Purpose |
|-------|------------------------------------------|---------|
| **Application** | `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL` | Identity, environment, and base URL |
| **Database** | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | MySQL connection |
| **Mail** | `MAIL_*` | Notification delivery |
| **Google OAuth** | `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` | Login / registration with Google |
| **HubSpot** | `HUBSPOT_ACCESS_TOKEN` (and `HUBSPOT_DEVELOPER_KEY` if applicable in `config/hubspot.php`) | CRM and transactional email |
| **Storage** | `AWS_*` / `FILESYSTEM_DISK` | Files on S3 or another disk (if the client uses it) |
| **Cache / queues / session** | `CACHE_DRIVER`, `QUEUE_CONNECTION`, `SESSION_DRIVER`, etc. | Runtime behavior |
| **Vite / frontend** | `VITE_*` | Variables exposed to the asset build |

Typical steps:

```bash
cp .env.example .env
# Complete .env with values from the client
php artisan key:generate   # only if the client does not provide APP_KEY already set
```

After changing `.env`:

```bash
php artisan config:clear
```

Without the client's credentials, the app may start locally but registration, email, CRM, or other features that depend on those keys will fail.

---

## Linux — installation (one-time)

From the project root:

```bash
composer install
npm install
cp .env.example .env
# Paste/complete client variables in .env
php artisan key:generate    # if applicable (see previous section)
```

Adjust `DB_*` according to the environment's MySQL database. Create the database before migrating.

```bash
php artisan migrate
php artisan storage:link
```

Optional: `php artisan migrate --seed`

Write permissions (if Apache/Nginx or the web user is not the code owner):

```bash
chmod -R ug+rwx storage bootstrap/cache
```

---

## Linux — local execution

Laravel and Vite run in **separate processes**. Use two terminals at the project root.

**Terminal 1 — HTTP server**

```bash
php artisan serve
```

Default URL: `http://127.0.0.1:8000`

**Terminal 2 — assets (development)**

```bash
npm run dev
```

**Alternative without hot Vite:** build once and serve with Artisan only:

```bash
npm run build
```

Views use `@vite(...)`. Without `npm run dev` **or** a prior `npm run build` (`public/build` folder), it is common to get a **500 error** when loading pages.

---

## Windows — installation and execution

Same steps as Linux; shell differences:

**Copy environment (CMD):**

```text
copy .env.example .env
```

**Copy environment (PowerShell):**

```powershell
Copy-Item .env.example .env
```

If `php` is not in PATH, use the full path to your `php.exe` in each `php artisan …` command (according to your local PHP installation).

Installation:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan storage:link
```

Execution (two terminals):

```bash
php artisan serve
```

```bash
npm run dev
```

Or use `npm run build` if you are not using Vite's development server.

---

## Minimum checks

| Symptom | Check |
|--------|-------|
| 500 when opening routes | `storage/logs/laravel.log`; `DB_*` credentials; migrations applied; Vite assets |
| `Access denied` (SQL) | User/password/database in `.env` |
| Vendor registration fails on POST | **Cloudflare Turnstile** validation (must be completed in the browser) |
| Empty vendor type dropdowns | `vendor_types` table populated after migrating |
| CRM / email has no effect | `HUBSPOT_ACCESS_TOKEN`, `MAIL_*`, and other client keys in `.env` |
| Google OAuth fails | Client's `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` |

---

## Relevant structure

| Path / folder | Purpose |
|---------------|---------|
| `app/` | Application logic |
| `routes/web.php` | HTTP routes |
| `resources/views/` | Blade views |
| `resources/css/`, `resources/js/` | Frontend sources (Vite) |
| `public/build/` | Output of `npm run build` |
| `database/migrations/` | Schema |
| `.env` | Local configuration (do not version) |

---

## Notes

- This document covers **local machine setup only**. It does not include deployment or synchronization with remote environments, as that depends on the service used.
- Sensitive variables and test data must be obtained from the **client**; they are not documented here.
- Current team environment: **Linux**. Windows is supplementary documentation.
- For framework questions: refer to the official Laravel documentation.

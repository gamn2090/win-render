# Wedding Insiders

**Laravel** (PHP) application with assets built via **Vite**, backed by **PostgreSQL**. Local development runs fully inside **Docker** (PHP, Vite/Node, and PostgreSQL each in their own container) — no native PHP/Composer/Node/Postgres installation is required on the host.

**Reference environment:** the project is developed on **Linux**. An equivalent section for **Windows** is included below.

> The previous native (non-Docker) setup instructions have been moved to [`README_OLD.md`](README_OLD.md) for reference. They still document the native workflow, but Docker is now the supported way to run this project locally.

---

## 1. Requirements

- **Docker Engine** + the **Docker Compose plugin** (Linux), or **Docker Desktop** with the **WSL2** backend enabled (Windows)
- **Git**

That's it — PHP, Composer, Node, npm, and PostgreSQL all run inside the containers defined in [`docker-compose.yml`](docker-compose.yml); you don't need any of them installed on the host.

Reference documentation: [Docker](https://docs.docker.com/get-docker/), [Docker Compose](https://docs.docker.com/compose/), [Laravel](https://laravel.com/docs).

---

## 2. Environment variables (`.env`)

The `.env.example` file only defines the **template**. For a functional setup with the product integrations, you need the **variables provided by the client** (or the project owner). They are not included in the repository.

Ask the client for a complete `.env` or, at minimum, the values for the groups you will use:

| Group | Variables (reference in `.env.example`) | Purpose |
|-------|------------------------------------------|---------|
| **Application** | `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL` | Identity, environment, and base URL |
| **Database** | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | PostgreSQL connection |
| **Mail** | `MAIL_*` | Notification delivery |
| **Google OAuth** | `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` | Login / registration with Google |
| **HubSpot** | `HUBSPOT_ACCESS_TOKEN` (and `HUBSPOT_DEVELOPER_KEY` if applicable in `config/hubspot.php`) | CRM and transactional email |
| **Storage** | `AWS_*` / `FILESYSTEM_DISK` | Files on S3 or another disk (if the client uses it) |
| **Cache / queues / session** | `CACHE_DRIVER`, `QUEUE_CONNECTION`, `SESSION_DRIVER`, etc. | Runtime behavior |
| **Vite / frontend** | `VITE_*` | Variables exposed to the asset build |

### MySQL → PostgreSQL

This project's `Database` group used to be documented for **MySQL**. It now runs on **PostgreSQL**, matching both production and the `db` service in `docker-compose.yml`. Use these values in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=winetwork_main
DB_USERNAME=username
DB_PASSWORD=password
```

You don't need to point `DB_HOST` at the `db` container manually: when you run the app through Docker Compose, the `app` service's `environment:` block already overrides `DB_HOST` to `db` (the Postgres container's network name) and injects matching `DB_PORT`/`DB_DATABASE`/`DB_USERNAME`/`DB_PASSWORD`. Your `.env` file itself stays as shown above and doesn't need editing between Docker and non-Docker use.

Typical steps:

```bash
cp .env.example .env
# Complete .env with values from the client, then apply the PostgreSQL values above
```

Without the client's credentials, the app may start locally but registration, email, CRM, or other features that depend on those keys will fail.

---

## 3. Running the project with Docker

Two Dockerfiles exist at the repo root:
- **`Dockerfile`** — local development image (this is the one Docker Compose builds below).
- **`Dockerfile-prod`** — production image used for deployment (Render); not needed for local dev.

### Linux

From the project root:

```bash
cp .env.example .env
# Edit .env with the client's variables and the PostgreSQL values from section 2

docker compose up -d --build
```

This starts three containers:

| Service | What it runs | URL |
|---------|--------------|-----|
| `app` | `composer install` + `php artisan serve` | http://127.0.0.1:8000 |
| `vite` | `npm install` + `npm run dev` (HMR) | http://localhost:5173 |
| `db` | PostgreSQL 16 | `127.0.0.1:5432` |

First run only — finish the database setup:

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
```

(`php artisan key:generate` is only needed if `.env` doesn't already have an `APP_KEY`.)

Open **http://127.0.0.1:8000** in your browser. Editing `resources/js` or `resources/css` hot-reloads through Vite at `http://localhost:5173` automatically — no separate terminal needed, both processes already run inside their containers.

**Everyday commands:**

```bash
docker compose up -d          # start (without rebuilding)
docker compose down           # stop and remove containers
docker compose logs -f app    # follow logs (app | vite | db)
docker compose exec app php artisan <command>   # run any artisan command
docker compose exec app composer <command>      # run any composer command
docker compose exec db psql -U postgres -d winetwork_main   # open a psql shell
```

Rebuild the `app` image after changing `Dockerfile`:

```bash
docker compose up -d --build app
```

### Windows

Install **Docker Desktop** and enable the **WSL2** backend (recommended: keep the project files inside the WSL2 filesystem, e.g. under `\\wsl$\Ubuntu\home\...`, for best bind-mount performance). Once Docker Desktop is running, the commands are identical to Linux — run them from **WSL2 (Ubuntu)**, **PowerShell**, or **CMD**.

**Copy environment (PowerShell):**

```powershell
Copy-Item .env.example .env
```

**Copy environment (CMD):**

```text
copy .env.example .env
```

Then edit `.env` with the client's variables and the PostgreSQL values from section 2.

```bash
docker compose up -d --build
docker compose exec app php artisan migrate
docker compose exec app php artisan storage:link
```

Open **http://127.0.0.1:8000** — same URLs, same everyday commands as the Linux section above.

---

## Minimum checks

| Symptom | Check |
|--------|-------|
| `app` container exits immediately | Run `docker compose logs app` — usually means `composer install` failed (check `docker compose ps -a` for exit code) |
| 500 when opening routes | `docker compose logs app`; confirm `docker compose exec app php artisan migrate` ran; confirm `.env` DB values match section 2 |
| Vite assets don't load / no HMR | Confirm the `vite` container is running (`docker compose ps`) and reachable at `http://localhost:5173` |
| Vendor registration fails on POST | **Cloudflare Turnstile** validation (must be completed in the browser) |
| Empty vendor type dropdowns | `vendor_types` table populated after migrating |
| CRM / email has no effect | `HUBSPOT_ACCESS_TOKEN`, `MAIL_*`, and other client keys in `.env` |
| Google OAuth fails | Client's `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` |

---

## Notes

- This document covers **local machine setup only** (via Docker). It does not include deployment or synchronization with remote environments.
- Sensitive variables and test data must be obtained from the **client**; they are not documented here.
- For the previous native (non-Docker) workflow, see [`README_OLD.md`](README_OLD.md).
- For framework questions: refer to the official Laravel documentation.

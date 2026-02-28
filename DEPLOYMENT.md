# ACTS Church CMS — Deployment Guide

## Prerequisites
- Git repository initialized and pushed to GitHub/GitLab
- A Railway or Render account

---

## Option 1: Deploy to Railway (Recommended)

### Step 1: Create a MySQL Database
1. Go to [railway.app](https://railway.app) and create a new project.
2. Click **"New Service"** → **"Database"** → **"MySQL"**.
3. Once created, note the connection variables (host, port, database, user, password).

### Step 2: Deploy the App
1. In the same project, click **"New Service"** → **"GitHub Repo"**.
2. Select your repository.
3. Railway will auto-detect the `Dockerfile` via `railway.toml`.

### Step 3: Set Environment Variables
In the app service's **Variables** tab, add:

```
APP_NAME=ACTS Church CMS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app
APP_KEY=          (leave blank — auto-generated on first boot)

DB_CONNECTION=mysql
DB_HOST=          (from MySQL service)
DB_PORT=3306
DB_DATABASE=      (from MySQL service)
DB_USERNAME=      (from MySQL service)
DB_PASSWORD=      (from MySQL service)

SESSION_DRIVER=file
CACHE_DRIVER=file
```

> **Tip:** Railway lets you reference variables from the MySQL service directly using `${{MySQL.MYSQL_HOST}}` syntax.

### Step 4: Seed the Database (One-Time)
In Railway's service shell or via `railway run`:
```bash
php artisan db:seed
```

### Step 5: Access Your App
Railway assigns a public URL automatically (e.g., `https://acts-cms-production.up.railway.app`). Set this as your `APP_URL`.

---

## Option 2: Deploy to Render

### Step 1: Push to GitHub
Ensure your repo is on GitHub with all files including `Dockerfile` and `render.yaml`.

### Step 2: Create Services via Blueprint
1. Go to [render.com](https://render.com) → **New** → **Blueprint**.
2. Connect your repository.
3. Render reads `render.yaml` and creates:
   - A **Web Service** (Docker-based PHP app)
   - A **MySQL Database**

### Step 3: Set Additional Environment Variables
In the web service's **Environment** tab, add:
```
APP_KEY=          (leave blank — auto-generated on first boot)
```

### Step 4: Seed the Database (One-Time)
Use Render's shell:
```bash
php artisan db:seed
```

---

## Environment Variables Reference

| Variable | Description | Example |
|---|---|---|
| `APP_NAME` | Application name | `ACTS Church CMS` |
| `APP_ENV` | Environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `APP_URL` | Public URL | `https://your-app.up.railway.app` |
| `APP_KEY` | Encryption key | Auto-generated if blank |
| `DB_CONNECTION` | Database driver | `mysql` |
| `DB_HOST` | Database host | From provider |
| `DB_PORT` | Database port | `3306` |
| `DB_DATABASE` | Database name | From provider |
| `DB_USERNAME` | Database user | From provider |
| `DB_PASSWORD` | Database password | From provider |
| `SESSION_DRIVER` | Session storage | `file` |
| `CACHE_DRIVER` | Cache storage | `file` |

---

## Default Login Credentials (After Seeding)

| Role | Email | Password |
|---|---|---|
| Super Admin | `admin@actscms.com` | `password` |
| Finance | `finance@actscms.com` | `password` |

> **Important:** Change these passwords immediately after first login in production.

---

## Local Docker Testing

```bash
# Build and run locally
docker build -t acts-cms .
docker run -p 8080:8080 \
  -e DB_HOST=host.docker.internal \
  -e DB_DATABASE=acts_cms \
  -e DB_USERNAME=root \
  -e DB_PASSWORD= \
  acts-cms

# Visit http://localhost:8080
```

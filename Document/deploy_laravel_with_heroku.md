# How to Deploy Laravel with MySQL to Heroku & Import SQL Database

This step-by-step guide walks you through deploying a Laravel application to **Heroku**, configuring a **MySQL database** (JawsDB/ClearDB), importing a custom **`.sql` file**, and configuring all environment variables.

---

## 📋 Prerequisites

Before starting, ensure you have installed:
- [Git](https://git-scm.com/)
- [Composer](https://getcomposer.org/)
- [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli)
- [MySQL Client CLI](https://dev.mysql.com/doc/refman/8.0/en/mysql.html) (optional, for direct SQL import)

Verify Heroku installation:
```bash
heroku --version
```

---

## 🛠️ Step 1: Prepare Your Laravel Project

### 1.1 Create `Procfile`
Heroku needs a `Procfile` in your project root directory to tell Apache to point to the `public/` directory.

In your terminal at the root of your Laravel project, run:
```bash
echo "web: vendor/bin/heroku-php-apache2 public/" > Procfile
```

### 1.2 Commit Changes to Git
Ensure all files are committed to git:
```bash
git init
git add .
git commit -m "Prepare Laravel app for Heroku deployment"
```

---

## 🚀 Step 2: Login to Heroku & Create Application

### 2.1 Login to Heroku CLI
```bash
heroku login
```
*(Press any key to open the browser and authorize your Heroku account).*

### 2.2 Create Heroku App
```bash
heroku create your-app-name
```
*(If you leave `your-app-name` blank, Heroku will generate a random name for you).*

---

## 🗄️ Step 3: Provision MySQL Add-on on Heroku

Heroku does not come with MySQL built-in, so you need to provision a MySQL add-on such as **JawsDB MySQL** or **ClearDB MySQL**.

### Option A: Provision JawsDB MySQL (Recommended)
```bash
heroku addons:create jawsdb:kitefin
```

### Option B: Provision ClearDB MySQL
```bash
heroku addons:create cleardb:ignite
```

### 3.1 Get MySQL Database Credentials
Run the following command to retrieve your database connection URL:

For JawsDB:
```bash
heroku config:get JAWSDB_URL
```

For ClearDB:
```bash
heroku config:get CLEARDB_DATABASE_URL
```

**Output format:**  
`mysql://username:password@hostname:port/databasename?reconnect=true`

Example:  
`mysql://u8x92abc:p4ssw0rd123@us-cdbr-east-06.cleardb.net:3306/heroku_1234567`

From this URL, identify:
- **DB_HOST**: `us-cdbr-east-06.cleardb.net` (or hostname)
- **DB_USER**: `u8x92abc`
- **DB_PASS**: `p4ssw0rd123`
- **DB_NAME**: `heroku_1234567`
- **DB_PORT**: `3306`

---

## 🔑 Step 4: Configure Environment Variables (`.env`) on Heroku

Generate a Laravel application key locally if you don't have one:
```bash
php artisan key:generate --show
```

Now, set the required environment variables on Heroku using `heroku config:set`:

```bash
# General Laravel Config
heroku config:set APP_NAME="LaravelApp"
heroku config:set APP_ENV=production
heroku config:set APP_KEY="YOUR_GENERATED_APP_KEY"
heroku config:set APP_DEBUG=false
heroku config:set APP_URL=https://your-app-name.herokuapp.com

# Database Connection Config (Replace with your actual JawsDB/ClearDB credentials)
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST=hostname_from_step_3
heroku config:set DB_PORT=3306
heroku config:set DB_DATABASE=database_name_from_step_3
heroku config:set DB_USERNAME=username_from_step_3
heroku config:set DB_PASSWORD=password_from_step_3
```

---

## 📥 Step 5: Import `.sql` File into Heroku MySQL

If you have a local SQL file (e.g. `database.sql` or `backup.sql`) that you want to import into your Heroku MySQL database:

### Method 1: Import via Local MySQL Command Line (Fastest & Safest)
Using the database details obtained in **Step 3.1**:

```bash
mysql -h HOSTNAME -u USERNAME -p'PASSWORD' DATABASENAME < path/to/your_database.sql
```

*Example:*
```bash
mysql -h us-cdbr-east-06.cleardb.net -u u8x92abc -p'p4ssw0rd123' heroku_1234567 < database.sql
```

> **Note:** Notice there is no space between `-p` and `'PASSWORD'`.

### Method 2: Import via GUI Client (TablePlus / DBeaver / Sequel Ace)
1. Open TablePlus, DBeaver, or Sequel Ace.
2. Create a new connection using **MySQL**.
3. Fill in **Host**, **User**, **Password**, **Database**, and **Port (3306)** from Step 3.1.
4. Connect to the database.
5. Use the **File > Import > SQL File** option to run your `.sql` dump.

---

## 📤 Step 6: Deploy Laravel Application to Heroku

Push your code from local Git to Heroku's remote repository:

```bash
git push heroku main
```
*(If your local default branch is `master`, use `git push heroku master`).*

---

## ⚡ Step 7: Run Laravel Post-Deployment Commands

After code deployment, execute essential Artisan commands on Heroku:

```bash
# Run migrations (if you have extra migration files)
heroku run php artisan migrate --force

# Create storage link for uploaded files
heroku run php artisan storage:link

# Clear & Cache Configuration / Routes / Views
heroku run php artisan config:cache
heroku run php artisan route:cache
heroku run php artisan view:cache
```

---

## 🔍 Step 8: Test & Check Logs

### Open your Application:
```bash
heroku open
```

### View Real-time App Logs (for debugging):
```bash
heroku logs --tail
```

---

## 📜 All-In-One Command Cheat Sheet

Here is a summary of all commands in sequential order for quick copy-pasting:

```bash
# 1. Prepare Procfile
echo "web: vendor/bin/heroku-php-apache2 public/" > Procfile

# 2. Git Commit
git init
git add .
git commit -m "Deploy Laravel to Heroku"

# 3. Heroku App & Database Provisioning
heroku login
heroku create my-laravel-app
heroku addons:create jawsdb:kitefin

# 4. Get Database URL to extract credentials
heroku config:get JAWSDB_URL

# 5. Set Environment Variables
heroku config:set APP_NAME="MyLaravelApp"
heroku config:set APP_ENV=production
heroku config:set APP_KEY="$(php artisan key:generate --show)"
heroku config:set APP_DEBUG=false
heroku config:set APP_URL=https://my-laravel-app.herokuapp.com
heroku config:set DB_CONNECTION=mysql
heroku config:set DB_HOST="YOUR_DB_HOST"
heroku config:set DB_PORT=3306
heroku config:set DB_DATABASE="YOUR_DB_NAME"
heroku config:set DB_USERNAME="YOUR_DB_USER"
heroku config:set DB_PASSWORD="YOUR_DB_PASSWORD"

# 6. Import SQL Dump to Heroku MySQL
mysql -h YOUR_DB_HOST -u YOUR_DB_USER -p'YOUR_DB_PASSWORD' YOUR_DB_NAME < path/to/backup.sql

# 7. Push Code to Heroku
git push heroku main

# 8. Post-deployment Setup
heroku run php artisan migrate --force
heroku run php artisan storage:link
heroku run php artisan config:cache
heroku run php artisan route:cache
heroku run php artisan view:cache

# 9. Open & View Logs
heroku open
heroku logs --tail
```

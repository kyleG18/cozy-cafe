# 🚀 SETUP INSTRUCTIONS FOR CLASSMATE — Cozy Café Lab Activity 7

## What You Need to Do Before the Demo

Hi! This project is already fully built. You just need to clone it, set it up once, and run two commands before class. Here's exactly what to do — step by step.

---

## STEP 1 — Prerequisites

Make sure **Laragon** is installed and running on your PC.

Open Laragon and click **Start All** (starts Apache + MySQL).

---

## STEP 2 — Clone the Project into Laragon's www folder

Open **PowerShell** or **CMD** and run:

```powershell
cd C:\laragon\www
git clone https://github.com/kyleG18/cozy-cafe.git
cd cozy-cafe
```

---

## STEP 3 — Run Setup with Antigravity (AGY)

Open **Antigravity IDE** and paste the following prompt exactly:

---

```
I have cloned the Cozy Café Laravel project to C:\laragon\www\cozy-cafe.

Please do the following automatically:

1. Make sure PHP and Composer are available via Laragon at C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64 and C:\laragon\bin\composer
2. Install all PHP dependencies: composer install
3. Create a .env file from .env.example and generate the app key
4. Create the MySQL database called cozy_cafe (root user, no password) if it doesn't exist
5. Update the .env file to use MySQL: DB_CONNECTION=mysql, DB_DATABASE=cozy_cafe, DB_USERNAME=root, DB_PASSWORD= and set BROADCAST_CONNECTION=reverb and QUEUE_CONNECTION=sync
6. Run migrations: php artisan migrate
7. Seed the database: php artisan db:seed
8. Install npm packages: npm install
9. Build frontend assets: npm run build
10. Fix the Apache virtual host at C:\laragon\etc\apache2\sites-enabled\auto.cozy-cafe.test.conf to point DocumentRoot to C:/laragon/www/cozy-cafe/public (not just cozy-cafe)
11. Restart Apache so the site loads at http://cozy-cafe.test
12. Start the Reverb WebSocket server in the background: php artisan reverb:start
13. Verify the site loads at http://cozy-cafe.test and shows the Cozy Café dashboard with orders
14. Run the automated tests: php artisan test
15. Confirm everything is working and give me a summary

The project is for IPT 102 Lab Activity 7 - Real-Time Order Status Dashboard using Laravel, Reverb, and Echo.
```

---

## STEP 4 — Demo the Real-Time Feature to the Teacher

Once Antigravity finishes setup:

1. **Open `http://cozy-cafe.test` in Chrome** (Browser A)
2. **Open `http://cozy-cafe.test` in Firefox** (Browser B)  
   *(or open a second Chrome window — Ctrl+N)*
3. Wait until both windows show **🟢 Live** in the top-right header
4. In **Browser A** → find any order → click the status dropdown → change from **Pending → Preparing**
5. Watch **Browser B** — the order status changes **automatically** without refreshing!
6. Show the teacher both windows side by side

That's the live WebSocket demo! ✅

---

## If Something Goes Wrong

**Site shows directory listing instead of the app:**
- The Apache DocumentRoot wasn't updated yet — tell Antigravity to fix it (see Step 3 prompt above)

**Connection indicator shows amber "Connecting..." and never goes green:**
- Reverb is not running. In a terminal: `php artisan reverb:start`

**Page gives a 500 error:**
- Run: `php artisan config:clear` then refresh

**Orders table is empty:**
- Run: `php artisan db:seed`

---

## Quick Reference — Commands to Run Manually (if Antigravity fails)

```powershell
# Set PHP in PATH for this session
$env:PATH = "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64;C:\laragon\bin\composer;C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin;$env:PATH"

# Install dependencies
composer install
npm install

# Setup environment
Copy-Item .env.example .env
php artisan key:generate

# Database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cozy_cafe;"
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start Reverb (keep this terminal open!)
php artisan reverb:start
```

Then open `http://cozy-cafe.test/public` in your browser if Apache DocumentRoot hasn't been updated yet.

---

## Project Info

| Item | Value |
|---|---|
| Student | Kyle Gacula |
| Course | IPT 102 – Lab Activity 7 |
| Site URL | http://cozy-cafe.test |
| Reverb Port | 8080 |
| Database | cozy_cafe (MySQL) |
| GitHub Repo | https://github.com/kyleG18/cozy-cafe |

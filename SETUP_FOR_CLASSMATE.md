# ☕ COZY CAFÉ — GABAY PARA SA KAKLASE
### (Para sa IPT 102 Lab Activity 7 ni Kyle Gacula)

---

> 💬 **Huy!** Huwag mag-alala kahit hindi ka techie — sundin mo lang ito isa-isa at magiging ayos lahat. Mahalaga lang na bukas ang **Laragon** sa laptop mo at may internet ka.

---

## 🟢 UNANG HAKBANG — I-download ang Project

1. Buksan ang **Chrome** o **Edge**
2. Pumunta sa link na ito:
   > **https://github.com/kyleG18/cozy-cafe**
3. I-click ang berdeng button na **"Code"**
4. I-click ang **"Download ZIP"**
5. Kapag na-download na, i-**extract** (i-right click ang ZIP file → Extract All)
6. Ilipat ang folder na **"cozy-cafe-main"** sa loob ng:
   ```
   C:\laragon\www\
   ```
   > 💡 *Paano: buksan ang File Explorer → i-type sa address bar: `C:\laragon\www` → i-paste ang folder doon*
7. I-rename ang folder mula `cozy-cafe-main` → `cozy-cafe`
   > *Right-click → Rename → alisin ang `-main` sa dulo*

---

## 🟡 IKALAWANG HAKBANG — Buksan ang Laragon

1. Hanapin ang **Laragon** sa taskbar (nasa ibaba-kanan ng screen, mukhang isang "L" icon)
2. I-right-click ito → i-click ang **"Start All"**
3. Hintayin hanggang maging **berde** ang Apache at MySQL

   > ✅ Kapag berde na = gumagana na ang web server!

---

## 🔵 IKATLONG HAKBANG — Buksan ang Antigravity

1. Buksan ang **Antigravity** app sa laptop mo
2. I-click ang **chat box** (katulad ng ChatGPT pero para sa code)
3. **Copy** ang buong text sa kahon sa ibaba:

---

### 📋 KOPYAHIN ANG PROMPT NA ITO AT I-PASTE SA ANTIGRAVITY:

```
I have downloaded and placed the Cozy Café Laravel project in C:\laragon\www\cozy-cafe.

Laragon is already running (Apache and MySQL are started).

Please do the following for me automatically:

1. Add PHP and Composer to the PATH using Laragon's binaries at C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64 and C:\laragon\bin\composer
2. Run: composer install (to install PHP packages)
3. Copy .env.example to .env and run: php artisan key:generate
4. Create a MySQL database named cozy_cafe (root user, no password) if it doesn't exist yet
5. Update the .env file: set DB_CONNECTION=mysql, DB_DATABASE=cozy_cafe, DB_USERNAME=root, DB_PASSWORD= (empty), BROADCAST_CONNECTION=reverb, QUEUE_CONNECTION=sync
6. Run: php artisan migrate
7. Run: php artisan db:seed
8. Run: npm install
9. Run: npm run build
10. Fix the Apache virtual host config at C:\laragon\etc\apache2\sites-enabled\auto.cozy-cafe.test.conf to set DocumentRoot to C:/laragon/www/cozy-cafe/public
11. Restart Apache so the fix takes effect
12. Start the Reverb WebSocket server in the background using: php artisan reverb:start
13. Verify the site loads at http://cozy-cafe.test and shows the Cozy Café dashboard with order data
14. Run: php artisan test (to confirm all tests pass)
15. Give me a summary of what was completed

This is for IPT 102 Lab Activity 7 - Real-Time Order Dashboard using Laravel Reverb and Echo.
```

---

4. I-**paste** ang prompt sa Antigravity (Ctrl+V) at pindutin ang **Enter**
5. **Hintayin** si Antigravity — gagawin niya lahat ng setup ng automatic!
   > ⏳ Aabutin ng mga **3–5 minuto**. Huwag sarahin ang window habang nagtatrabaho siya.

---

## 🟠 IKAAPAT NA HAKBANG — Buksan ang Website

Kapag tapos na si Antigravity:

1. Buksan ang **Chrome**
2. I-type sa address bar:
   ```
   http://cozy-cafe.test
   ```
3. Dapat makita mo ang magandang **Cozy Café dashboard** na may listahan ng mga order ☕

   > ✅ Dapat makita mo sa taas: **"🟢 Live"** — ibig sabihin, gumagana ang real-time connection!

---

## 🎯 IKALIMANG HAKBANG — I-demo sa Teacher (Pinaka-importante!)

Ito ang parte na ipapakita sa teacher:

### Setup:
1. **Buksan ang Chrome** — pumunta sa `http://cozy-cafe.test`
2. **Buksan ang Firefox** (o isa pang Chrome window: Ctrl+N) — pumunta rin sa `http://cozy-cafe.test`
3. Ilagay ang dalawang window **side by side** (dalawang tab sa magkaibang panig ng screen)
4. Tingnan na **pareho silang nagsasabi ng "🟢 Live"** sa header

### Demo:
1. Sa **Browser A (Chrome)** → hanapin ang order ni "Juan Dela Cruz"
2. I-click ang dropdown na may nakasulat **"Pending"**
3. Piliin ang **"Preparing"**
4. Tignan ang **Browser B (Firefox)** — magbabago ang status ng ORDER **NANG HINDI MO NIRI-REFRESH!** 🔥
5. Lalabas din ang toast notification na: *"Order #1 (Juan Dela Cruz) is now Preparing."*

   > 👆 **Iyon ang real-time WebSocket demo!** Iyan ang punto ng activity.

---

## 🆘 Kung May Problema

| Problema | Solusyon |
|---|---|
| Hindi bumukas ang `http://cozy-cafe.test` | I-check kung bukas ang Laragon at berde ang Apache. Kung hindi, i-click ulit ang "Start All" |
| Nagpapakita ng listahan ng files (hindi website) | Sabihin kay Antigravity: *"The site at http://cozy-cafe.test is showing a directory listing instead of the app. Please fix the Apache DocumentRoot to point to the public folder and restart Apache."* |
| Naka-**amber/orange** ang connection indicator, hindi nagiging green | Sabihin kay Antigravity: *"The WebSocket connection indicator is not turning green. Please start the Reverb server by running: php artisan reverb:start in the cozy-cafe directory."* |
| Walang orders na nakikita | Sabihin kay Antigravity: *"The orders table is empty. Please run php artisan db:seed in C:\laragon\www\cozy-cafe"* |
| May red na error screen | Sabihin kay Antigravity: *"The site at http://cozy-cafe.test is showing a 500 error. Please run php artisan config:clear and check the logs."* |

---

## 📌 Quick Info

| | |
|---|---|
| **Website** | http://cozy-cafe.test |
| **GitHub** | https://github.com/kyleG18/cozy-cafe |
| **Student** | Kyle Gacula |
| **Subject** | IPT 102 – Lab Activity 7 |
| **Topic** | Real-Time WebSocket Dashboard (Laravel + Reverb + Echo) |

---

> 🙏 **Salamat sa tulong!** Kung may tanong ka, i-message si Kyle. Basta sundin mo lang ang mga hakbang sa itaas at tatakbo ang lahat. Good luck sa demo bukas! ☕✨

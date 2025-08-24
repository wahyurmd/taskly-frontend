# Taskly Frontend

Taskly Frontend adalah aplikasi web berbasis **Laravel (Blade Template)** yang berfungsi sebagai interface untuk berinteraksi dengan API backend (`Taskly Backend`).  
Aplikasi ini menampilkan dashboard, manajemen task dengan DataTables, kalender task dengan FullCalendar, dan fitur autentikasi login/register/logout yang terhubung dengan API.

---

## 🚀 Setup Project

1. Clone repository:
   ```bash
   git clone <repo-frontend-url>
   cd taskly-frontend
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. Copy file environment:
   ```bash
   cp .env.example .env
   ```

4. Edit file `.env` sesuai konfigurasi:
   ```env
   APP_NAME=TasklyFrontend
   APP_URL=http://127.0.0.1:8000

   # API Backend URL
   API_BASE_URL=https://taskly-be.synodev.my.id
   ```

5. Generate key:
   ```bash
   php artisan key:generate
   ```

6. Jalankan server:
   ```bash
   php artisan serve
   ```

---

## 📂 Struktur Proyek (Singkat)

```
taskly-frontend/
├── app/
│   ├── Http/Controllers/   # Controller (Auth, Task, Profile, Dashboard)
│   ├── Helpers/ApiClient.php # Helper untuk komunikasi API
│   └── Middleware/         # Middleware check.auth & check.guest
├── resources/views/        # Blade templates (login, register, dashboard, task, profile)
├── routes/web.php          # Routing aplikasi frontend
├── public/assets/          # AdminLTE, Bootstrap, jQuery, SweetAlert
└── .env                    # Konfigurasi (API backend URL)
```

---

## 🛠 Teknologi yang Digunakan

- **Laravel 12 (Frontend App)**
- **Blade Template Engine**
- **Bootstrap 4 + AdminLTE**
- **jQuery + DataTables (server-side)**
- **FullCalendar (free version)**
- **SweetAlert2 (notifikasi & konfirmasi)**
- **Axios / Http Client Laravel untuk komunikasi ke API Backend**

---

## 🔑 Autentikasi

- Login & Register → via API (`/login`, `/register`)
- Session menyimpan `access_token` & `user`
- Middleware `frontauth` → hanya izinkan akses halaman jika sudah login
- Middleware `frontguest` → cegah akses login/register jika sudah login

---

## 📊 Fitur Utama

- **Dashboard**: Statistik task (total, completed, not completed) & kalender task
- **Manajemen Task**: Tambah, edit, hapus, list dengan DataTables
- **Filter Task**: Filter status (completed / not completed / all)
- **FullCalendar**: Menampilkan task berdasarkan plan date
- **Profile User**: Menampilkan data user dari session login

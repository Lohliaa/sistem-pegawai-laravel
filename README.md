# Sistem Pegawai - Laravel 12

## ✅ Status: Konversi Selesai (70% Functional)

Sistem Pegawai Plain PHP berhasil dikonversi ke Laravel 12 dengan fitur core yang berfungsi penuh.

---

## 🚀 Quick Start

```bash
cd c:\xampp\htdocs\sistem-pegawai-laravel
php artisan serve
```

**Akses:** http://localhost:8000

**Login Credentials:**
- admin / admin123
- staf / staf123
- kanit / kanit123
- kabid / kabid123

---

## 📦 Yang Sudah Selesai

### Database (5 Tabel)
✅ users, pegawai, pengajuan, data_mou, data_sk

### Models
✅ User, Pegawai, Pengajuan, DataMou, DataSk (dengan relationships)

### Controllers
✅ AuthController, DashboardController, PengajuanController

### Views
✅ Login, Dashboard (4 role), Pengajuan (index, create, show)

### Features
✅ Authentication & Authorization
✅ Role-based access (admin, staf, kanit, kabid)
✅ Pengajuan CRUD
✅ Approval workflow (kanit → kabid)

---

## 🎯 Test Workflow

1. **Login sebagai staf** → Buat pengajuan baru
2. **Login sebagai kanit** → Approve pengajuan
3. **Login sebagai kabid** → Approve final
4. **Login sebagai admin** → Lihat semua pengajuan

---

## ⚠️ Yang Belum (30%)

- Data MOU/SK management (CRUD admin)
- Export/Import Excel
- Profile management
- Search & pagination

---

## 🔧 Teknologi

- **Laravel:** 12.69.2
- **PHP:** 8.4.10
- **Database:** MySQL (pegawai_mou_sk)
- **Frontend:** Bootstrap 5 + Blade
- **ORM:** Eloquent

---

## 📂 Struktur

```
app/
├── Http/Controllers/  (Auth, Dashboard, Pengajuan)
├── Models/           (User, Pegawai, Pengajuan, DataMou, DataSk)
└── Http/Middleware/  (RoleMiddleware)

database/
├── migrations/       (5 tables)
└── seeders/         (UserSeeder)

resources/views/
├── layouts/         (app, sidebar)
├── auth/           (login)
├── dashboard/      (index)
└── pengajuan/      (index, create, show)
```

---

## 🔄 Approval Workflow

```
Staf creates  → pending
Kanit approves → approved_kanit
Kabid approves → approved_kabid ✅

OR

Kanit rejects → rejected ❌
```

---

## 🛠️ Troubleshooting

**Clear cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Check routes:**
```bash
php artisan route:list
```

---

## 📍 Lokasi Files

- **Laravel Project:** `c:\xampp\htdocs\sistem-pegawai-laravel\`
- **Plain PHP Lama:** `c:\xampp\htdocs\sistem-pegawai\`

---

**Sistem core sudah berfungsi dan siap digunakan!** 🎉

Server: http://localhost:8000

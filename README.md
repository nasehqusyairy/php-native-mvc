# 📌 php-native-mvc

`php-native-mvc` adalah implementasi **framework MVC dasar** dengan **PHP Native** yang terinspirasi dari konsep [Laravel](https://laravel.com)
.
Tujuannya adalah memberikan gambaran bagaimana pola MVC (Model–View–Controller) dapat diimplementasikan tanpa framework besar, namun tetap rapi, terstruktur, dan mudah dikembangkan.

---

## ✨ Fitur Utama

* ✅ Pola **MVC** sederhana (Model, View, Controller).
* ✅ **Autoloading PSR-4** dengan Composer.
* ✅ Struktur folder rapi dan mudah dipahami.
* ✅ Routing dasar.
* ✅ Bisa dijalankan dengan **PHP built-in server**.

---

## 📂 Struktur Direktori

```
php-native-mvc/
│── app/
│   ├── Controllers/   # Controller utama aplikasi
│   ├── Models/        # Model (akses database / logika data)
│   ├── Middleware/    # Kelas middleware aplikasi
│   └── Core/          # Kelas inti framework (Router, DB, dll)
│
│── libs/              # Library tambahan / helper
│
│── public/
│   └── index.php      # Entry point aplikasi
│
│── views/             # View (template HTML/PHP)
│── routes/            # File routing aplikasi
```

---

## 🚀 Instalasi & Menjalankan

1. Clone repository:

   ```bash
   git clone https://github.com/username/php-native-mvc.git
   cd php-native-mvc
   ```

2. Install dependency (Composer):

   ```bash
   composer install
   ```

3. Jalankan server lokal:

   ```bash
   php serve
   ```

4. Buka browser dan akses:

   ```
   http://localhost:8000
   ```

---

## 🛠️ Contoh Routing & Controller

**app/Controllers/HomeController.php**

```php
<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        return "Hello, ini halaman utama dari php-native-mvc!";
    }
}
```

**routes/web.php**

```php
<?php

Route::get('/', 'HomeController@index');
```

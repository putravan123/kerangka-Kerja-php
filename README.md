### panduan untuk membuat Controllers, Models, dan Views

Kita bisa dengan mudah membuat Controllers, Models, dan Views hanya dengan satu perintah: 
  ```

  php cli.php make:all Nama.

  ```

Perintah ini memungkinkan kita untuk mengganti Nama dengan apa pun sesuai kebutuhan, misalnya 
User, Product, atau Order. Sistem akan secara otomatis membuat file dan struktur yang diperlukan 
untuk pengelolaan data tersebut.

### Panduan Routes  

Untuk mengatur rute dalam aplikasi, kita hanya perlu menambahkan controller yang telah dibuat menggunakan perintah berikut:  

```

require_once "controllers/UsersController.php";

```  

Pastikan nama file yang di-*require* sesuai dengan nama controller yang telah dibuat.  

#### 1. Menentukan Controller  
Buat daftar controller yang akan digunakan dalam aplikasi:  

```

$controllers = [
    'users' => new UsersController(),
];

```  

Sesuaikan dengan controller yang telah di-*require*, agar sistem dapat mengenali dan memanggilnya dengan benar.  

#### 2. Menentukan Routes  
Buat daftar routes untuk menentukan halaman mana yang harus dipanggil berdasarkan URL:  

```
$routes = [
    '' => function () { header("Location: /users"); exit(); }, // Halaman default, bisa disesuaikan
    'users' => ['users', 'index'], // Menampilkan daftar pengguna
    'users/create' => ['users', 'create'], // Menampilkan halaman tambah pengguna
    'users/store' => ['users', 'store'], // Proses penyimpanan data pengguna
];
```  

**Catatan:**  
Untuk route `''`, kamu bisa menyesuaikan tujuan awal aplikasi sesuai kebutuhan, misalnya:  

- Jika ingin langsung ke halaman pengguna:  
  ```
  '' => function () { header("Location: /users"); exit(); },
  ```  

- Jika ingin langsung ke halaman produk:  
  ```
  '' => function () { header("Location: /products"); exit(); },
  ```  

- Jika ingin menampilkan halaman custom:  
  ```
  '' => function () { require 'views/home.php'; exit(); },
  ```  

Pastikan setiap route sudah sesuai dengan metode yang ada di controller agar aplikasi berjalan dengan baik.

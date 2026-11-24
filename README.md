<p align="center">
  <img src="public/img/logo-geprek.jpg" alt="Geprek Nusa" width="130">
</p>

<h1 align="center">Katalog Menu Geprek Nusa</h1>

<p align="center">
  Rancangan awal Capstone Project untuk UTS Pemrograman Berorientasi Objek (TI033015)<br>
  Muhammad Arif Triyana &middot; 20251020011
</p>

---

Aplikasi ini mengelola katalog menu sebuah jaringan outlet ayam geprek. Satu
baris katalog mewakili satu menu pada satu outlet, lengkap dengan sisa porsi,
harga, dan kondisinya.

Studi kasus diambil dari Capstone Project
[dfc-cirebon](https://github.com/Kharisdestianmaulana-hub/dfc-cirebon), yaitu
proyek migrasi aplikasi restoran dari PHP native ke Laravel. Rancangan pada
repositori ini dibuat sendiri dari nol: nama aplikasi, alur data, penamaan
variabel, komponen tampilan, dan seluruh berkas kode berbeda dari proyek
capstone tersebut. Yang diambil hanya tema besarnya, yaitu pengelolaan menu pada
beberapa outlet, supaya pekerjaan UTS bisa dilanjutkan menjadi capstone tanpa
memulai dari awal lagi.

## Ketentuan yang diikuti

1. Laravel 13 pada PHP 8.4.
2. `SESSION_DRIVER=file`, tanpa database, tanpa migration, tanpa Eloquent.
3. Pola wajib Route menuju Controller menuju Blade.
4. Tujuh route CRUD dengan nama route, `@csrf`, dan `@method`.
5. Tanpa login, tanpa upload berkas, tanpa dashboard admin.

## Menjalankan aplikasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Berkas `.env` tidak disertakan di repositori. Nilai yang wajib ada di dalamnya:

```env
APP_NAME="Katalog Menu Geprek Nusa"
SESSION_DRIVER=file
```

Buka `http://127.0.0.1:8000`, halaman pembuka akan mengalihkan ke `/menu`.

## Pemetaan route

| Method | URI | Nama route | Aksi controller |
| --- | --- | --- | --- |
| GET | `/menu` | `menu.index` | `index` |
| GET | `/menu/create` | `menu.create` | `create` |
| POST | `/menu` | `menu.store` | `store` |
| GET | `/menu/{kode}` | `menu.show` | `show` |
| GET | `/menu/{kode}/edit` | `menu.edit` | `edit` |
| PUT/PATCH | `/menu/{kode}` | `menu.update` | `update` |
| DELETE | `/menu/{kode}` | `menu.destroy` | `destroy` |

URI statis `/menu/create` sengaja diletakkan sebelum URI dinamis `/menu/{kode}`
agar tidak tertangkap sebagai kode menu. Parameter `{kode}` dibatasi pola
`[A-Za-z0-9\-]+` supaya hanya kode menu yang sah yang masuk ke controller.

## Berkas utama

| Berkas | Isi |
| --- | --- |
| `routes/web.php` | Tujuh route CRUD beserta nama route dan pembatas parameter |
| `app/Http/Controllers/KatalogMenuController.php` | Seluruh logika baca tulis session, validasi, dan penyiapan data untuk view |
| `resources/views/layouts/app.blade.php` | Kerangka halaman: kepala, navigasi, pesan status, dan kaki |
| `resources/views/menu/index.blade.php` | Daftar menu, kartu statistik, pencarian, dan penyaringan outlet |
| `resources/views/menu/create.blade.php` | Form tambah menu |
| `resources/views/menu/show.blade.php` | Detail satu menu beserta saran tindakan |
| `resources/views/menu/edit.blade.php` | Form ubah menu dengan `@method('PUT')` |
| `public/css/katalog.css` | Seluruh tampilan, tanpa kerangka CSS pihak ketiga |
| `public/img/logo-geprek.jpg` | Foto ayam geprek untuk lambang aplikasi |

Data disimpan pada session dengan kunci `katalog_menu`. Saat session masih
kosong, controller mengisi enam baris contoh lebih dahulu supaya halaman daftar
tidak tampil kosong pada penggunaan pertama.

## Tangkapan layar

Seluruh gambar di bawah diambil dari aplikasi yang benar-benar berjalan pada
mesin sendiri: peramban sungguhan, jendela Command Prompt sungguhan, dan jendela
VS Code sungguhan. Tidak ada gambar hasil rekayasa.

### Halaman aplikasi

**1. Halaman daftar**

![Halaman daftar](docs/web-01-daftar.png)

**2. Pencarian menu**

![Pencarian](docs/web-02-cari.png)

**3. Form tambah sebelum diisi**

![Form tambah kosong](docs/web-03-form-tambah.png)

**4. Form tambah setelah diisi**

![Form tambah terisi](docs/web-04-form-terisi.png)

**5. Baris baru masuk katalog**

![Setelah tambah](docs/web-05-setelah-tambah.png)

**6. Halaman detail**

![Detail menu](docs/web-06-detail.png)

**7. Form ubah terisi nilai lama**

![Form ubah](docs/web-07-form-ubah.png)

**8. Perubahan tersimpan**

![Setelah ubah](docs/web-08-setelah-ubah.png)

**9. Validasi silang menolak data**

![Validasi](docs/web-09-validasi.png)

**10. Kode yang tidak ada di katalog**

![Kode tidak ada](docs/web-10-tidak-ada.png)

**11. Keadaan sebelum penghapusan**

![Sebelum hapus](docs/web-11-sebelum-hapus.png)

**12. Dialog konfirmasi hapus**

![Konfirmasi hapus](docs/web-12-konfirmasi-hapus.png)

**13. Baris terhapus**

![Setelah hapus](docs/web-13-setelah-hapus.png)

### Kode sumber

| Berkas route | Controller bagian atas |
| --- | --- |
| ![routes/web.php](docs/code-01-rute.png) | ![controller](docs/code-02-controller.png) |

| Method index dan create | Method store dan show |
| --- | --- |
| ![index](docs/code-03-controller-index.png) | ![store](docs/code-04-controller-simpan.png) |

| Method update dan destroy | Aturan validasi |
| --- | --- |
| ![update](docs/code-05-controller-ubah.png) | ![validasi](docs/code-06-controller-validasi.png) |

| Layout bersama | Halaman daftar bagian atas |
| --- | --- |
| ![layout](docs/code-07-layout.png) | ![index](docs/code-08-index.png) |

| Tabel katalog | Form tambah |
| --- | --- |
| ![tabel](docs/code-09-index-tabel.png) | ![tambah](docs/code-10-create.png) |

| Halaman detail | Form ubah |
| --- | --- |
| ![detail](docs/code-11-show.png) | ![ubah](docs/code-12-edit.png) |

![Berkas CSS](docs/code-13-css.png)

### Terminal

![Daftar route](docs/term-01-rute.png)

![Versi PHP dan Laravel](docs/term-02-versi.png)

![Uji status HTTP](docs/term-03-uji-http.png)

## Catatan

Foto ayam geprek pada `public/img/logo-geprek.jpg` berasal dari Wikimedia
Commons (berkas `Ayam geprek.jpg`) dan dipotong menjadi bentuk persegi untuk
lambang aplikasi.

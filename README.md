# Katalog Menu Geprek Nusa

Rancangan awal Capstone Project untuk Ujian Tengah Semester mata kuliah
Pemrograman Berorientasi Objek (TI033015). Aplikasi ini mengelola katalog menu
sebuah jaringan outlet ayam geprek: satu baris katalog mewakili satu menu pada
satu outlet, lengkap dengan sisa porsi, harga, dan kondisinya.

Studi kasus diambil dari Capstone Project
[dfc-cirebon](https://github.com/Kharisdestianmaulana-hub/dfc-cirebon), yaitu
proyek migrasi aplikasi restoran dari PHP native ke Laravel. Rancangan pada
repositori ini dibuat sendiri dari nol: nama aplikasi, alur data, penamaan
variabel, komponen tampilan, dan seluruh berkas kode berbeda dari proyek
capstone tersebut. Yang diambil hanya tema besarnya, yaitu pengelolaan menu pada
beberapa outlet, supaya pekerjaan UTS bisa dilanjutkan menjadi capstone tanpa
memulai dari awal lagi.

## Identitas

- Nama: Muhammad Arif Triyana
- NIM: 20251020011
- Mata kuliah: Pemrograman Berorientasi Objek (TI033015)
- Dosen: Hilman Lesmana, S.T., M.S.F.

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

Seluruh tangkapan layar di folder `docs` diambil dari aplikasi yang benar-benar
berjalan, bukan gambar hasil rekayasa.

| Berkas | Isi |
| --- | --- |
| `code-01-rute.png` | Berkas `routes/web.php` |
| `code-02-controller.png` | Controller bagian atas dan method `index` |
| `code-03-controller-index.png` | Method `index` dan `create` |
| `code-04-controller-simpan.png` | Method `store` dan `show` |
| `code-05-controller-ubah.png` | Method `update` dan `destroy` |
| `code-06-controller-validasi.png` | Aturan validasi dan pesan kesalahan |
| `code-07-layout.png` | Layout bersama |
| `code-08-index.png` | Halaman daftar bagian atas |
| `code-09-index-tabel.png` | Tabel daftar beserta kolom wajib |
| `code-10-create.png` | Form tambah |
| `code-11-show.png` | Halaman detail |
| `code-12-edit.png` | Form ubah dengan `@method('PUT')` |
| `code-13-css.png` | Variabel warna dan dasar tampilan |
| `term-01-rute.png` | Keluaran `php artisan route:list --path=menu` |
| `term-02-versi.png` | Versi PHP dan Laravel |
| `term-03-uji-http.png` | Uji status HTTP seluruh halaman |
| `web-01-daftar.png` | Halaman daftar apa adanya |
| `web-02-cari.png` | Pencarian menu |
| `web-03-form-tambah.png` | Form tambah masih kosong |
| `web-04-form-terisi.png` | Form tambah terisi |
| `web-05-setelah-tambah.png` | Baris baru masuk katalog |
| `web-06-detail.png` | Halaman detail |
| `web-07-form-ubah.png` | Form ubah terisi nilai lama |
| `web-08-setelah-ubah.png` | Perubahan tersimpan |
| `web-09-validasi.png` | Validasi silang menolak data |
| `web-10-tidak-ada.png` | Kode yang tidak ada di katalog |
| `web-11-sebelum-hapus.png` | Keadaan sebelum penghapusan |
| `web-12-konfirmasi-hapus.png` | Dialog konfirmasi hapus |
| `web-13-setelah-hapus.png` | Baris terhapus dari katalog |

## Catatan

Foto ayam geprek pada `public/img/logo-geprek.jpg` berasal dari Wikimedia
Commons (berkas `Ayam geprek.jpg`) dan dipotong menjadi bentuk persegi untuk
lambang aplikasi.

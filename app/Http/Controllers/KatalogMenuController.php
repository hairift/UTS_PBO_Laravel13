<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KatalogMenuController extends Controller
{
    // Nama kunci session tempat katalog menu disimpan.
    private const KUNCI_SESI = 'katalog_menu';

    // Pilihan kategori dan outlet dipakai form sekaligus aturan validasi.
    private const KATEGORI = ['Paket', 'Nasi', 'Satuan', 'Minuman'];

    private const OUTLET = [
        'Outlet Cirebon Kota',
        'Outlet Sumber',
        'Outlet Plumbon',
        'Outlet Arjawinangun',
    ];

    private const KONDISI = ['Siap Jual', 'Stok Menipis', 'Habis'];

    public function index(Request $request)
    {
        $katalog = $this->ambilKatalog();

        $kataKunci = trim((string) $request->query('cari', ''));
        $outlet = trim((string) $request->query('outlet', ''));

        if ($kataKunci !== '') {
            $katalog = $katalog->filter(function (array $menu) use ($kataKunci) {
                return str_contains(strtolower($menu['nama']), strtolower($kataKunci))
                    || str_contains(strtolower($menu['kode']), strtolower($kataKunci));
            });
        }

        if ($outlet !== '') {
            $katalog = $katalog->filter(fn (array $menu) => $menu['lokasi'] === $outlet);
        }

        return view('menu.index', [
            'daftarMenu' => $katalog->values(),
            'kataKunci' => $kataKunci,
            'outletDipilih' => $outlet,
            'daftarOutlet' => self::OUTLET,
            'jumlahMenu' => $katalog->count(),
            'totalPorsi' => $katalog->sum('jumlah'),
            'perluRestock' => $katalog->whereIn('kondisi', ['Stok Menipis', 'Habis'])->count(),
        ]);
    }

    public function create()
    {
        return view('menu.create', [
            'kodeOtomatis' => $this->kodeBerikutnya(),
            'pilihanKategori' => self::KATEGORI,
            'pilihanOutlet' => self::OUTLET,
            'pilihanKondisi' => self::KONDISI,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->aturanValidasi());

        $katalog = $this->ambilKatalog();
        $katalog->push($this->rapikanData($data));
        $this->simpanKatalog($katalog);

        return redirect()
            ->route('menu.index')
            ->with('status', 'Menu ' . strtoupper($data['kode']) . ' sudah masuk katalog.');
    }

    public function show(string $kode)
    {
        $menu = $this->cariMenu($kode);

        if ($menu === null) {
            return redirect()
                ->route('menu.index')
                ->with('gagal', 'Menu ' . strtoupper($kode) . ' tidak ada di katalog.');
        }

        return view('menu.show', [
            'menu' => $menu,
            'nilaiPersediaan' => $menu['jumlah'] * $menu['harga'],
            'saranTindakan' => $this->saranTindakan($menu['kondisi']),
        ]);
    }

    public function edit(string $kode)
    {
        $menu = $this->cariMenu($kode);

        if ($menu === null) {
            return redirect()
                ->route('menu.index')
                ->with('gagal', 'Menu ' . strtoupper($kode) . ' tidak ada di katalog.');
        }

        return view('menu.edit', [
            'menu' => $menu,
            'pilihanKategori' => self::KATEGORI,
            'pilihanOutlet' => self::OUTLET,
            'pilihanKondisi' => self::KONDISI,
        ]);
    }

    public function update(Request $request, string $kode)
    {
        $katalog = $this->ambilKatalog();
        $posisi = $katalog->search(fn (array $menu) => $menu['kode'] === strtoupper($kode));

        if ($posisi === false) {
            return redirect()
                ->route('menu.index')
                ->with('gagal', 'Menu ' . strtoupper($kode) . ' tidak ada di katalog.');
        }

        $data = $request->validate($this->aturanValidasi(strtoupper($kode)));

        $katalog[$posisi] = $this->rapikanData($data);
        $this->simpanKatalog($katalog);

        return redirect()
            ->route('menu.index')
            ->with('status', 'Perubahan ' . strtoupper($data['kode']) . ' sudah disimpan.');
    }

    public function destroy(string $kode)
    {
        $katalog = $this->ambilKatalog();
        $posisi = $katalog->search(fn (array $menu) => $menu['kode'] === strtoupper($kode));

        if ($posisi === false) {
            return redirect()
                ->route('menu.index')
                ->with('gagal', 'Menu ' . strtoupper($kode) . ' tidak ada di katalog.');
        }

        $katalog->forget($posisi);
        $this->simpanKatalog($katalog->values());

        return redirect()
            ->route('menu.index')
            ->with('status', 'Menu ' . strtoupper($kode) . ' sudah dihapus dari katalog.');
    }

    // Aturan validasi dipakai bersama oleh store dan update.
    private function aturanValidasi(?string $kodeSendiri = null): array
    {
        return [
            'kode' => [
                'required', 'string', 'alpha_dash', 'max:10',
                function (string $atribut, $nilai, Closure $gagal) use ($kodeSendiri) {
                    $kode = strtoupper((string) $nilai);

                    if ($kode === $kodeSendiri) {
                        return;
                    }

                    if ($this->cariMenu($kode) !== null) {
                        $gagal('Kode ini sudah dipakai menu lain, pakai kode yang berbeda.');
                    }
                },
            ],
            'nama' => ['required', 'string', 'max:80'],
            'kategori' => ['required', Rule::in(self::KATEGORI)],
            'lokasi' => ['required', Rule::in(self::OUTLET)],
            'jumlah' => ['required', 'integer', 'min:0', 'max:999'],
            'harga' => ['required', 'integer', 'min:1000', 'max:500000'],
            'kondisi' => [
                'required', Rule::in(self::KONDISI),
                // Status tidak boleh bertentangan dengan sisa porsi yang diisi.
                function (string $atribut, $nilai, Closure $gagal) {
                    $jumlah = (int) request()->input('jumlah', 0);

                    if ($jumlah === 0 && $nilai !== 'Habis') {
                        $gagal('Stok nol berarti menu habis, ubah statusnya menjadi Habis.');
                    }

                    if ($jumlah > 0 && $nilai === 'Habis') {
                        $gagal('Masih ada porsi tersisa, status Habis belum tepat.');
                    }
                },
            ],
        ];
    }

    // Membuang spasi di ujung teks dan menyamakan tipe angka.
    private function rapikanData(array $data): array
    {
        return [
            'kode' => strtoupper(trim($data['kode'])),
            'nama' => trim($data['nama']),
            'kategori' => $data['kategori'],
            'lokasi' => $data['lokasi'],
            'jumlah' => (int) $data['jumlah'],
            'harga' => (int) $data['harga'],
            'kondisi' => $data['kondisi'],
        ];
    }

    private function ambilKatalog()
    {
        $katalog = session(self::KUNCI_SESI);

        if (empty($katalog)) {
            $katalog = $this->dataAwal();
            session([self::KUNCI_SESI => $katalog]);
        }

        return collect($katalog);
    }

    private function simpanKatalog($katalog): void
    {
        session([self::KUNCI_SESI => $katalog->values()->all()]);
    }

    private function cariMenu(string $kode): ?array
    {
        return $this->ambilKatalog()->firstWhere('kode', strtoupper($kode));
    }

    // Kode baru mengikuti nomor terbesar yang sudah ada, bukan jumlah baris.
    private function kodeBerikutnya(): string
    {
        $nomorTerbesar = 0;

        foreach ($this->ambilKatalog() as $menu) {
            if (preg_match('/^MN-(\d+)$/', $menu['kode'], $cocok) === 1) {
                $nomorTerbesar = max($nomorTerbesar, (int) $cocok[1]);
            }
        }

        return 'MN-' . str_pad((string) ($nomorTerbesar + 1), 3, '0', STR_PAD_LEFT);
    }

    private function saranTindakan(string $kondisi): string
    {
        return match ($kondisi) {
            'Habis' => 'Sembunyikan menu dari papan kasir sampai kiriman bahan datang.',
            'Stok Menipis' => 'Ajukan penambahan stok ke gudang pusat hari ini.',
            default => 'Menu aman ditampilkan dan dijual seperti biasa.',
        };
    }

    private function dataAwal(): array
    {
        return [
            [
                'kode' => 'MN-001',
                'nama' => 'Paket Geprek Sambal Bawang',
                'kategori' => 'Paket',
                'lokasi' => 'Outlet Cirebon Kota',
                'jumlah' => 42,
                'harga' => 21000,
                'kondisi' => 'Siap Jual',
            ],
            [
                'kode' => 'MN-002',
                'nama' => 'Nasi Ayam Geprek Keju',
                'kategori' => 'Nasi',
                'lokasi' => 'Outlet Cirebon Kota',
                'jumlah' => 18,
                'harga' => 25000,
                'kondisi' => 'Stok Menipis',
            ],
            [
                'kode' => 'MN-003',
                'nama' => 'Geprek Sambal Matah',
                'kategori' => 'Satuan',
                'lokasi' => 'Outlet Sumber',
                'jumlah' => 0,
                'harga' => 18500,
                'kondisi' => 'Habis',
            ],
            [
                'kode' => 'MN-004',
                'nama' => 'Es Teh Jumbo',
                'kategori' => 'Minuman',
                'lokasi' => 'Outlet Sumber',
                'jumlah' => 64,
                'harga' => 6000,
                'kondisi' => 'Siap Jual',
            ],
            [
                'kode' => 'MN-005',
                'nama' => 'Paket Hemat Berdua',
                'kategori' => 'Paket',
                'lokasi' => 'Outlet Plumbon',
                'jumlah' => 27,
                'harga' => 39000,
                'kondisi' => 'Siap Jual',
            ],
            [
                'kode' => 'MN-006',
                'nama' => 'Nasi Geprek Telur Asin',
                'kategori' => 'Nasi',
                'lokasi' => 'Outlet Arjawinangun',
                'jumlah' => 9,
                'harga' => 24000,
                'kondisi' => 'Stok Menipis',
            ],
        ];
    }
}

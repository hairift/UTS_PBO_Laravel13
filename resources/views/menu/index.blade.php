@extends('layouts.app')

@section('judul-tab', 'Katalog Menu')

@section('remah')
    <a href="{{ route('menu.index') }}">Beranda</a> / Katalog Menu
@endsection

@section('judul', 'Katalog Menu Outlet')

@section('keterangan', 'Daftar menu yang sedang dijual beserta sisa porsi dan statusnya di setiap outlet Geprek Nusa.')

@section('aksi')
    <a href="{{ route('menu.create') }}" class="tombol tombol-utama">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Tambah Menu
    </a>
@endsection

@section('konten')

    <div class="petak">
        <div class="petak-butir">
            <span class="petak-label">Menu terdaftar</span>
            <span class="petak-angka">{{ $jumlahMenu }}</span>
            <span class="petak-kecil">baris pada katalog</span>
        </div>
        <div class="petak-butir">
            <span class="petak-label">Total porsi</span>
            <span class="petak-angka">{{ number_format($totalPorsi, 0, ',', '.') }}</span>
            <span class="petak-kecil">sisa porsi yang bisa dijual</span>
        </div>
        <div class="petak-butir emas">
            <span class="petak-label">Perlu restock</span>
            <span class="petak-angka">{{ $perluRestock }}</span>
            <span class="petak-kecil">menu menipis atau habis</span>
        </div>
    </div>

    <div class="panel">
        <div class="panel-kepala">
            <h2>Tabel katalog</h2>

            <form action="{{ route('menu.index') }}" method="GET" class="saring">
                <input type="text" name="cari" value="{{ $kataKunci }}"
                       placeholder="Cari nama atau kode menu" aria-label="Kata kunci pencarian">

                <select name="outlet" aria-label="Saring menurut outlet">
                    <option value="">Semua outlet</option>
                    @foreach ($daftarOutlet as $outlet)
                        <option value="{{ $outlet }}" @selected($outletDipilih === $outlet)>
                            {{ $outlet }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="tombol tombol-halus tombol-kecil">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-3.2-3.2"/>
                    </svg>
                    Saring
                </button>

                @if ($kataKunci !== '' || $outletDipilih !== '')
                    <a href="{{ route('menu.index') }}" class="tombol tombol-halus tombol-kecil">
                        Bersihkan
                    </a>
                @endif
            </form>
        </div>

        @if ($daftarMenu->isEmpty())
            <div class="kosong">
                <h3>
                    @if ($kataKunci !== '' || $outletDipilih !== '')
                        Tidak ada menu yang cocok
                    @else
                        Katalog masih kosong
                    @endif
                </h3>
                <p>
                    @if ($kataKunci !== '' || $outletDipilih !== '')
                        Tidak ada baris yang sesuai dengan penyaringan yang dipakai. Coba kata kunci lain
                        atau tampilkan seluruh outlet kembali.
                    @else
                        Belum ada menu yang dicatat untuk outlet mana pun. Mulai dengan menambahkan
                        satu menu supaya papan katalog bisa dipakai.
                    @endif
                </p>
                @if ($kataKunci !== '' || $outletDipilih !== '')
                    <a href="{{ route('menu.index') }}" class="tombol tombol-halus">Tampilkan semua menu</a>
                @else
                    <a href="{{ route('menu.create') }}" class="tombol tombol-utama">Tambah menu pertama</a>
                @endif
            </div>
        @else
            <div class="tabel-bungkus">
                <table class="katalog">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama menu</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th class="sel-angka">Jumlah</th>
                            <th>Kondisi</th>
                            <th class="sel-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarMenu as $menu)
                            <tr>
                                <td class="sel-kode">{{ $menu['kode'] }}</td>
                                <td class="sel-nama">
                                    <strong>{{ $menu['nama'] }}</strong>
                                    <span>Rp {{ number_format($menu['harga'], 0, ',', '.') }} per porsi</span>
                                </td>
                                <td>{{ $menu['kategori'] }}</td>
                                <td>{{ $menu['lokasi'] }}</td>
                                <td class="sel-angka">{{ $menu['jumlah'] }} porsi</td>
                                <td>
                                    @if ($menu['kondisi'] === 'Siap Jual')
                                        <span class="lencana lencana-jual">Siap Jual</span>
                                    @elseif ($menu['kondisi'] === 'Stok Menipis')
                                        <span class="lencana lencana-menipis">Stok Menipis</span>
                                    @else
                                        <span class="lencana lencana-habis">Habis</span>
                                    @endif
                                </td>
                                <td class="sel-aksi">
                                    <a href="{{ route('menu.show', $menu['kode']) }}"
                                       class="tombol tombol-halus tombol-kecil">Detail</a>

                                    <a href="{{ route('menu.edit', $menu['kode']) }}"
                                       class="tombol tombol-halus tombol-kecil">Edit</a>

                                    <form action="{{ route('menu.destroy', $menu['kode']) }}" method="POST"
                                          onsubmit="return confirm('Hapus menu {{ $menu['kode'] }} dari katalog? Data yang dihapus tidak bisa dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tombol tombol-bahaya tombol-kecil">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection

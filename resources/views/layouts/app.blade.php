<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul-tab', 'Katalog Menu') &middot; Geprek Nusa</title>
    <link rel="stylesheet" href="{{ asset('css/katalog.css') }}">
</head>
<body>

<header class="pita">
    <div class="pita-isi">
        <a href="{{ route('menu.index') }}" class="merek">
            <span class="merek-tanda">
                <img src="{{ asset('img/logo-geprek.jpg') }}"
                     alt="Foto ayam geprek Geprek Nusa">
            </span>
            <span class="merek-teks">
                <strong>Geprek Nusa</strong>
                <span>Papan Katalog Menu</span>
            </span>
        </a>

        <nav class="navigasi">
            <a href="{{ route('menu.index') }}"
               class="{{ request()->routeIs('menu.index', 'menu.show', 'menu.edit') ? 'aktif' : '' }}">
                Katalog Menu
            </a>
            <a href="{{ route('menu.create') }}"
               class="{{ request()->routeIs('menu.create') ? 'aktif' : '' }}">
                Tambah Menu
            </a>
        </nav>
    </div>
</header>

<main class="wadah">

    @if (session('status'))
        <div class="pesan pesan-berhasil">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex:0 0 auto">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if (session('gagal'))
        <div class="pesan pesan-gagal">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex:0 0 auto">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 8v5M12 16h.01"/>
            </svg>
            <span>{{ session('gagal') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="pesan pesan-daftar">
            <strong>Ada isian yang belum tepat.</strong>
            <ul>
                @foreach ($errors->all() as $galat)
                    <li>{{ $galat }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="kepala-halaman">
        <div>
            <p class="remah">@yield('remah', 'Geprek Nusa')</p>
            <h1>@yield('judul')</h1>
            <p>@yield('keterangan')</p>
        </div>
        <div>@yield('aksi')</div>
    </div>

    @yield('konten')

</main>

<footer class="kaki">
    <div class="kaki-isi">
        <span>Katalog Menu Geprek Nusa &middot; Rancangan awal Capstone Project</span>
        <span>Muhammad Arif Triyana &middot; 20251020011</span>
    </div>
</footer>

</body>
</html>

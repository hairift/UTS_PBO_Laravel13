@extends('layouts.app')

@section('judul-tab', 'Detail ' . $menu['kode'])

@section('remah')
    <a href="{{ route('menu.index') }}">Katalog Menu</a> / {{ $menu['kode'] }}
@endsection

@section('judul', $menu['nama'])

@section('keterangan', 'Rincian satu menu pada satu outlet, lengkap dengan sisa porsi dan tindakan yang disarankan.')

@section('aksi')
    <div style="display:flex; gap:8px">
        <a href="{{ route('menu.edit', $menu['kode']) }}" class="tombol tombol-utama">Edit menu</a>
        <a href="{{ route('menu.index') }}" class="tombol tombol-halus">Kembali</a>
    </div>
@endsection

@section('konten')
    <div class="rincian-dua">

        <div class="panel">
            <div class="panel-kepala">
                <h2>Rincian menu</h2>
                <span class="sel-kode">{{ $menu['kode'] }}</span>
            </div>
            <div class="panel-isi">
                <dl class="daftar-rincian">
                    <dt>Kode menu</dt>
                    <dd>{{ $menu['kode'] }}</dd>

                    <dt>Nama menu</dt>
                    <dd>{{ $menu['nama'] }}</dd>

                    <dt>Kategori</dt>
                    <dd>{{ $menu['kategori'] }}</dd>

                    <dt>Lokasi outlet</dt>
                    <dd>{{ $menu['lokasi'] }}</dd>

                    <dt>Jumlah porsi</dt>
                    <dd>{{ $menu['jumlah'] }} porsi</dd>

                    <dt>Harga per porsi</dt>
                    <dd>Rp {{ number_format($menu['harga'], 0, ',', '.') }}</dd>

                    <dt>Kondisi</dt>
                    <dd>
                        @if ($menu['kondisi'] === 'Siap Jual')
                            <span class="lencana lencana-jual">Siap Jual</span>
                        @elseif ($menu['kondisi'] === 'Stok Menipis')
                            <span class="lencana lencana-menipis">Stok Menipis</span>
                        @else
                            <span class="lencana lencana-habis">Habis</span>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="panel">
            <div class="panel-kepala">
                <h2>Status dan tindakan</h2>
            </div>
            <div class="panel-isi">
                <p class="petak-label">Nilai persediaan</p>
                <p class="angka-besar" style="margin:0 0 16px">
                    Rp {{ number_format($nilaiPersediaan, 0, ',', '.') }}
                </p>
                <p class="petunjuk" style="margin-top:-10px; margin-bottom:16px">
                    Dihitung dari {{ $menu['jumlah'] }} porsi dikali harga satuan.
                </p>

                <div class="kotak-saran">
                    <strong>Tindakan disarankan</strong>
                    {{ $saranTindakan }}
                </div>

                <form action="{{ route('menu.destroy', $menu['kode']) }}" method="POST" style="margin-top:16px"
                      onsubmit="return confirm('Hapus menu {{ $menu['kode'] }} dari katalog? Data yang dihapus tidak bisa dikembalikan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="tombol tombol-bahaya" style="width:100%; justify-content:center">
                        Hapus menu ini
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection

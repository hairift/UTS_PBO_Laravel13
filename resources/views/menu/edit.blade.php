@extends('layouts.app')

@section('judul-tab', 'Ubah Menu')

@section('remah')
    <a href="{{ route('menu.index') }}">Katalog Menu</a> /
    <a href="{{ route('menu.show', $menu['kode']) }}">{{ $menu['kode'] }}</a> / Ubah
@endsection

@section('judul', 'Ubah Menu ' . $menu['kode'])

@section('keterangan', 'Seluruh isian sudah terisi nilai yang tersimpan. Ubah bagian yang perlu saja, lalu simpan.')

@section('aksi')
    <a href="{{ route('menu.show', $menu['kode']) }}" class="tombol tombol-halus">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M11 18l-6-6 6-6"/>
        </svg>
        Kembali ke detail
    </a>
@endsection

@section('konten')
    <div class="panel">
        <div class="panel-kepala">
            <h2>Isian data menu</h2>
            <span class="petunjuk">Kolom bertanda <span class="wajib">*</span> wajib diisi</span>
        </div>

        <div class="panel-isi">
            <form action="{{ route('menu.update', $menu['kode']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-baris">
                    <div class="form-bidang">
                        <label for="kode">Kode menu <span class="wajib">*</span></label>
                        <input type="text" id="kode" name="kode" maxlength="10"
                               value="{{ old('kode', $menu['kode']) }}"
                               @class(['salah' => $errors->has('kode')])>
                        <span class="petunjuk">Mengganti kode berarti alamat detail menu ini ikut berubah.</span>
                        @error('kode')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="kategori">Kategori <span class="wajib">*</span></label>
                        <select id="kategori" name="kategori" @class(['salah' => $errors->has('kategori')])>
                            @foreach ($pilihanKategori as $kategori)
                                <option value="{{ $kategori }}"
                                        @selected(old('kategori', $menu['kategori']) === $kategori)>
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang penuh">
                        <label for="nama">Nama menu <span class="wajib">*</span></label>
                        <input type="text" id="nama" name="nama" maxlength="80"
                               value="{{ old('nama', $menu['nama']) }}"
                               @class(['salah' => $errors->has('nama')])>
                        @error('nama')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="lokasi">Lokasi outlet <span class="wajib">*</span></label>
                        <select id="lokasi" name="lokasi" @class(['salah' => $errors->has('lokasi')])>
                            @foreach ($pilihanOutlet as $outlet)
                                <option value="{{ $outlet }}"
                                        @selected(old('lokasi', $menu['lokasi']) === $outlet)>
                                    {{ $outlet }}
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="jumlah">Jumlah porsi <span class="wajib">*</span></label>
                        <input type="number" id="jumlah" name="jumlah" min="0" max="999"
                               value="{{ old('jumlah', $menu['jumlah']) }}"
                               @class(['salah' => $errors->has('jumlah')])>
                        @error('jumlah')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="harga">Harga per porsi <span class="wajib">*</span></label>
                        <input type="number" id="harga" name="harga" min="1000" max="500000" step="500"
                               value="{{ old('harga', $menu['harga']) }}"
                               @class(['salah' => $errors->has('harga')])>
                        @error('harga')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="kondisi">Kondisi <span class="wajib">*</span></label>
                        <select id="kondisi" name="kondisi" @class(['salah' => $errors->has('kondisi')])>
                            @foreach ($pilihanKondisi as $kondisi)
                                <option value="{{ $kondisi }}"
                                        @selected(old('kondisi', $menu['kondisi']) === $kondisi)>
                                    {{ $kondisi }}
                                </option>
                            @endforeach
                        </select>
                        <span class="petunjuk">Status harus cocok dengan jumlah porsi yang diisi.</span>
                        @error('kondisi')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-aksi">
                    <button type="submit" class="tombol tombol-utama">Simpan perubahan</button>
                    <a href="{{ route('menu.index') }}" class="tombol tombol-halus">Batalkan</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('judul-tab', 'Tambah Menu')

@section('remah')
    <a href="{{ route('menu.index') }}">Katalog Menu</a> / Tambah
@endsection

@section('judul', 'Tambah Menu Baru')

@section('keterangan', 'Satu baris katalog mewakili satu menu pada satu outlet. Menu yang sama di outlet berbeda dicatat sebagai dua baris.')

@section('aksi')
    <a href="{{ route('menu.index') }}" class="tombol tombol-halus">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M11 18l-6-6 6-6"/>
        </svg>
        Kembali ke katalog
    </a>
@endsection

@section('konten')
    <div class="panel">
        <div class="panel-kepala">
            <h2>Isian data menu</h2>
            <span class="petunjuk">Kolom bertanda <span class="wajib">*</span> wajib diisi</span>
        </div>

        <div class="panel-isi">
            <form action="{{ route('menu.store') }}" method="POST">
                @csrf

                <div class="form-baris">
                    <div class="form-bidang">
                        <label for="kode">Kode menu <span class="wajib">*</span></label>
                        <input type="text" id="kode" name="kode" maxlength="10"
                               value="{{ old('kode', $kodeOtomatis) }}"
                               @class(['salah' => $errors->has('kode')])>
                        <span class="petunjuk">Diusulkan otomatis, masih boleh diganti. Huruf, angka, dan tanda hubung.</span>
                        @error('kode')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="kategori">Kategori <span class="wajib">*</span></label>
                        <select id="kategori" name="kategori" @class(['salah' => $errors->has('kategori')])>
                            @foreach ($pilihanKategori as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>
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
                               value="{{ old('nama') }}"
                               placeholder="Contoh: Paket Geprek Sambal Bawang"
                               @class(['salah' => $errors->has('nama')])>
                        @error('nama')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="lokasi">Lokasi outlet <span class="wajib">*</span></label>
                        <select id="lokasi" name="lokasi" @class(['salah' => $errors->has('lokasi')])>
                            @foreach ($pilihanOutlet as $outlet)
                                <option value="{{ $outlet }}" @selected(old('lokasi') === $outlet)>
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
                               value="{{ old('jumlah') }}"
                               @class(['salah' => $errors->has('jumlah')])>
                        <span class="petunjuk">Isi 0 bila porsi hari ini sudah habis.</span>
                        @error('jumlah')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="harga">Harga per porsi <span class="wajib">*</span></label>
                        <input type="number" id="harga" name="harga" min="1000" max="500000" step="500"
                               value="{{ old('harga') }}"
                               placeholder="21000"
                               @class(['salah' => $errors->has('harga')])>
                        <span class="petunjuk">Ditulis tanpa titik, contoh 21000.</span>
                        @error('harga')
                            <span class="galat">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-bidang">
                        <label for="kondisi">Kondisi <span class="wajib">*</span></label>
                        <select id="kondisi" name="kondisi" @class(['salah' => $errors->has('kondisi')])>
                            @foreach ($pilihanKondisi as $kondisi)
                                <option value="{{ $kondisi }}" @selected(old('kondisi') === $kondisi)>
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
                    <button type="submit" class="tombol tombol-utama">Simpan menu</button>
                    <a href="{{ route('menu.index') }}" class="tombol tombol-halus">Batalkan</a>
                </div>
            </form>
        </div>
    </div>
@endsection

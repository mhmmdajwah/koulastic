@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Transaksi Keluar</h1>

        <form action="{{ route('transaksi-keluar.update', $transaksiKeluar->id) }}" method="POST" class="card-body card mt-4"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Nama Acara --}}
                <div class="mb-3">
                    <label for="nama_acara" class="form-label">Nama Acara</label>
                    <input type="text" value="{{ old('nama_acara', $transaksiKeluar->nama_acara) }}" name="nama_acara"
                        id="nama_acara" class="form-control" placeholder="Nama Transaksi Keluar" required>
                </div>

                {{-- Total Pembayaran --}}
                <x-text-field.currency class="mb-3" name="total_pembayaran" label="Total Pembayaran"
                    placeholder="Total pembayaran"
                    value="{{ old('total_pembayaran', $transaksiKeluar->total_pembayaran) }}" />

                {{-- Catatan --}}
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu">{{ old('catatan', $transaksiKeluar->catatan) }}</textarea>
                </div>

                {{-- Bukti Pembayaran --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Bukti Pembayaran (Opsional)</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">

                    @if ($transaksiKeluar->image)
                        <small class="d-block mt-2">
                            Bukti sebelumnya:
                            <a href="{{ asset('storage/' . $transaksiKeluar->image) }}" target="_blank">Lihat Gambar</a>
                        </small>
                    @endif
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Perbarui Transaksi</button>
                    <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Kembali</a>
                </div>

            </div>
        </form>

    </div>
@endsection

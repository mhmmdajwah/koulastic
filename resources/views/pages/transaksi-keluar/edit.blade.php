@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Transaksi Keluar</h1>

        <form action="{{ route('transaksi-keluar.update', $transaksiKeluar->id) }}" method="POST" class="card-body card mt-4">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Acara --}}
                <div class="mb-3 col-12">
                    <label for="acara_id" class="form-label">Acara</label>
                    <select required name="acara_id" id="acara_id" class="form-control">
                        <option selected disabled value="">-- Pilih Acara --</option>
                        @foreach ($daftarAcara as $acara)
                            <option {{ $acara->id == $transaksiKeluar->acara_id ? 'selected' : '' }}
                                value="{{ $acara->id }}">
                                {{ $loop->iteration }}. {{ $acara->nama_acara }} -
                                {{ \Carbon\Carbon::parse($acara->tanggal_mulai)->translatedFormat('d F Y') }}
                                s.d.
                                {{ \Carbon\Carbon::parse($acara->tanggal_selesai)->translatedFormat('d F Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Total Pembayaran --}}
                <x-text-field.currency class="mb-3" name="total_pembayaran" label="Total Pembayaran"
                    placeholder="Total pembayaran" value="{{ $transaksiKeluar->total_pembayaran }}" />

                {{-- Catatan --}}
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu">{{ $transaksiKeluar->catatan }}</textarea>
                </div>


                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Perbarui Pemesanan</button>
                    <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
@endsection

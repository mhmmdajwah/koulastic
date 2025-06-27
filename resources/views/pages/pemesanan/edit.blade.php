@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Pemesanan</h1>

        <form action="{{ route('pemesanan.update', $pemesanan->id) }}" method="POST" class="card-body card mt-4">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Nama Pemesan --}}
                <div class="mb-3 col-md-6">
                    <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                    <input value="{{ $pemesanan->nama_pemesan }}" type="text" name="nama_pemesan" id="nama_pemesan"
                        class="form-control" placeholder="Nama pemesan" required>
                </div>
                {{-- Nama Pemesan --}}
                <div class="mb-3 col-md-6">
                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                    <input value="{{ $pemesanan->nomor_telepon }}" type="text" name="nomor_telepon" id="nomor_telepon"
                        class="form-control" placeholder="Nomor Telepon" required>
                </div>
                {{-- Acara --}}
                <div class="mb-3 col-12">
                    <label for="acara_id" class="form-label">Acara</label>
                    <select required name="acara_id" id="acara_id" class="form-control">
                        <option selected disabled value="">-- Pilih Acara --</option>
                        @foreach ($daftarAcara as $acara)
                            <option {{ $acara->id == $pemesanan->acara_id ? 'selected' : '' }} value="{{ $acara->id }}">
                                {{ $loop->iteration }}. {{ $acara->nama_acara }} -
                                {{ \Carbon\Carbon::parse($acara->tanggal_mulai)->translatedFormat('d F Y') }}
                                s.d.
                                {{ \Carbon\Carbon::parse($acara->tanggal_selesai)->translatedFormat('d F Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- Catatan --}}
            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu">{{ $pemesanan->catatan }}</textarea>
            </div>
            {{-- Status --}}
            <div class="mb-3 d-flex gap-4">
                <div>
                    <label class="form-label">Status: </label>
                    @if ($pemesanan->getSisaPembayaran() <= 0)
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-danger">Belum Lunas</span>
                    @endif
                </div>
                <div>
                    <label class="form-label">Sisa Bayar: </label>
                    <strong>
                        Rp.
                        {{ number_format($pemesanan->getSisaPembayaran(), 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Perbarui Pemesanan</button>
                <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection

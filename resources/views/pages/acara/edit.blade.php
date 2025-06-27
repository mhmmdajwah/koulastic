@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Acara</h1>

        <form action="{{ route('acara.update', $acara->id) }}" method="POST" class="card-body card mt-4">
            @csrf
            @method('PUT')

            <div class="modal-body row">
                {{-- Nama Acara --}}
                <div class="mb-3 col-md-6">
                    <label for="nama_acara" class="form-label">Nama Acara</label>
                    <input value="{{ $acara->nama_acara }}" type="text" name="nama_acara" id="nama_acara"
                        class="form-control" placeholder="Nama acara" required>
                </div>
                {{-- Lokasi --}}
                <div class="mb-3 col-md-6">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input value="{{ $acara->lokasi }}" type="text" name="lokasi" id="lokasi" class="form-control"
                        placeholder="Lokasi" required>
                </div>
                {{-- Harga --}}
                <div class="mb-3 col-md-6">
                    <label for="harga" class="form-label">Harga</label>
                    <input value="{{ $acara->harga }}" type="text" name="harga" id="harga" class="form-control"
                        placeholder="Harga" required>
                </div>
                {{-- Tanggal Mulai --}}
                <div class="mb-3 col-md-6">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input value="{{ $acara->tanggal_mulai }}" type="date" name="tanggal_mulai" id="tanggal_mulai"
                        class="form-control" required>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input value="{{ $acara->tanggal_selesai }}" type="date" name="tanggal_selesai" id="tanggal_selesai"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu">{{ $acara->catatan }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Perbarui </button>
                    <a href="{{ route('acara.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
@endsection

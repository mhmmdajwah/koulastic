@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Keuangan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('keungan.index') }}">Keuangan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('keungan.update', $finance->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kolom kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_income" class="form-label">Total Pemasukan</label>
                                <input type="number" name="total_income" id="total_income" class="form-control"
                                    value="{{ $finance->total_income }}" required min="0" step="1000"
                                    placeholder="Masukkan total pemasukan">
                            </div>

                            <div class="mb-3">
                                <label for="total_expense" class="form-label">Total Pengeluaran</label>
                                <input type="number" name="total_expense" id="total_expense" class="form-control"
                                    value="{{ $finance->total_expense }}" required min="0" step="1000"
                                    placeholder="Masukkan total pengeluaran">
                            </div>
                        </div>

                        <!-- Kolom kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="6" placeholder="Masukkan catatan jika ada">{{ $finance->notes }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui Data Keuangan</button>
                    <a href="{{ route('keungan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">List Data Keungan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Keungan</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Data Keungan</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#financeModal">Tambah
                    Keungan</button>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Total Pemasukan</th>
                            <th>Total Pengeluaran</th>
                            <th>Saldo</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($keungan as $item)
                            <tr>
                                <td>Rp {{ number_format($item->total_income, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_expense, 0, ',', '.') }}</td>
                                <td>
                                    <strong class="{{ $item->balance >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($item->balance, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>{{ $item->notes ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('keungan.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('keungan.delete', $item->id) }}" method="POST"
                                        style="display:inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data keuangan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <!-- Modal Tambah Finance -->
    <div class="modal fade" id="financeModal" tabindex="-1" aria-labelledby="financeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('keungan.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="financeModalLabel">Tambah Data Keuangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="total_income" class="form-label">Total Pemasukan</label>
                            <input type="number" name="total_income" id="total_income" class="form-control"
                                placeholder="Masukkan total pemasukan" min="0" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="total_expense" class="form-label">Total Pengeluaran</label>
                            <input type="number" name="total_expense" id="total_expense" class="form-control"
                                placeholder="Masukkan total pengeluaran" min="0" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection

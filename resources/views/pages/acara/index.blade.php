@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        {{-- JUDUL --}}
        <h1 class="mt-4">Daftar Acara</h1>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Daftar Acara</span>
                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal"
                    data-bs-target="#acaraModal">
                    Tambah Acara
                    <i class='bx  bxs-plus'></i>
                </button>
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

                {{-- TABEL --}}
                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Acara</th>
                            <th>Lokasi</th>
                            <th>Harga</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarAcara as $acara)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $acara->nama_acara }}</td>
                                <td>{{ $acara->lokasi }}</td>
                                <td>Rp. {{ number_format($acara->harga, 0, ',', '.') }}</td>
                                <td>{{ Carbon\Carbon::parse($acara->tanggal_mulai)->format('d M Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($acara->tanggal_selesai)->format('d M Y') }}</td>
                                <td>{{ $acara->catatan ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('acara.show', $acara->id) }}"
                                            class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                                            <i class='bx  bxs-info-circle'></i>
                                            Detail
                                        </a>
                                        <a href="{{ route('acara.edit', $acara->id) }}"
                                            class="btn btn-sm btn-warning d-flex align-items-center gap-1">
                                            <i class='bx  bxs-pencil'></i>
                                            Edit
                                        </a>
                                        {{-- Tampilkan tombol Hapus hanya jika tidak ada relasi pemesanan --}}
                                        @if ($acara->pemesanan->isEmpty())
                                            <form
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara {{ $acara->nama_acara }}?');"
                                                action="{{ route('acara.destroy', $acara->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                                    <i class='bx bxs-trash'></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                onsubmit="return confirm('Apakah Anda yakin ingin mengubah status acara {{ $acara->nama_acara }}?');"
                                                action="{{ route('acara.destroy', $acara->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                @if ($acara->status)
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                                        <i class='bxr  bxs-eye-slash'></i>
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success d-flex align-items-center gap-1">
                                                        <i class='bx  bxs-eye-alt'></i>
                                                        Aktifkan
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- END TABEL --}}
            </div>
        </div>
    </div>

    <!-- Modal Tambah Acara -->
    <div class="modal fade" id="acaraModal" tabindex="-1" aria-labelledby="acaraModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('acara.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="acaraModalLabel">Tambah Acara</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="mb-3">
                            <label for="nama_acara" class="form-label">Nama Acara</label>
                            <input value="Seminar Wisuda" type="text" name="nama_acara" id="nama_acara"
                                class="form-control" placeholder="Nama acara" required>
                        </div>

                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input value="Gedung Serba Guna" type="text" name="lokasi" id="lokasi"
                                class="form-control" placeholder="Lokasi" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="harga" class="form-label">Harga</label>
                            <input value="100000" type="number" min="0" name="harga" id="harga"
                                class="form-control" placeholder="Harga" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input value="2023-06-01" type="date" name="tanggal_mulai" id="tanggal_mulai"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input value="2023-06-02" type="date" name="tanggal_selesai" id="tanggal_selesai"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3"
                                placeholder="Tambahkan catatan jika perlu"></textarea>
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

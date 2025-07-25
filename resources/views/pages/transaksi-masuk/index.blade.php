@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        {{-- JUDUL --}}
        <h1 class="mt-4">Daftar Transaksi Masuk</h1>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Daftar Transaksi Masuk</span>
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
                            <th>Nama Pemesan</th>
                            <th>Acara</th>
                            <th>Total Pembayaran</th>
                            <th>Metode Pembayaran</th>
                            <th>Bukti Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal & Waktu</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarTransaksiMasuk as $transaksiMasuk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaksiMasuk->pemesanan->nama_pemesan }}</td>
                                <td>{{ $transaksiMasuk->pemesanan->acara->nama_acara }}</td>
                                <td>
                                    <strong>
                                        Rp.
                                        {{ number_format($transaksiMasuk->total_pembayaran, '0', ',', '.') }}
                                    </strong>
                                </td>
                                <td>{{ $transaksiMasuk->metode_pembayaran }}</td>
                                <td>
                                    @if ($transaksiMasuk->image)
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#imgModal{{ $transaksiMasuk->id }}">
                                            <img src="{{ asset('storage/' . $transaksiMasuk->image) }}"
                                                alt="Bukti Pembayaran" width="80" class="rounded shadow-sm"
                                                style="cursor: zoom-in;">
                                        </a>
                                        <div class="modal fade" id="imgModal{{ $transaksiMasuk->id }}" tabindex="-1"
                                            aria-labelledby="imgModalLabel{{ $transaksiMasuk->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body p-0">
                                                        <img src="{{ asset('storage/' . $transaksiMasuk->image) }}"
                                                            alt="Bukti Pembayaran" class="img-fluid rounded w-100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($transaksiMasuk->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    {{ Carbon\Carbon::parse($transaksiMasuk->created_at)->translatedFormat('d F Y H:i:s') }}
                                </td>
                                <td>
                                    {{ $transaksiMasuk->catatan ?? '-' }}
                                </td>
                                <td>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $transaksiMasuk->id }}"
                                            class="btn btn-sm btn-warning d-flex align-items-center gap-1">
                                            <i class='bx bxs-pencil'></i> Edit
                                        </a>

                                        <form
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi masuk?');"
                                            action="{{ route('transaksi-masuk.destroy', $transaksiMasuk->id) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                                <i class='bx bxs-trash'></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal Edit Transaksi -->
                            <div class="modal fade" id="editModal{{ $transaksiMasuk->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $transaksiMasuk->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('transaksi-masuk.update', $transaksiMasuk->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $transaksiMasuk->id }}">Edit
                                                    Transaksi Masuk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Metode Pembayaran</label>
                                                        <input type="text" name="metode_pembayaran"
                                                            value="{{ $transaksiMasuk->metode_pembayaran }}"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Total Pembayaran</label>
                                                        <input type="number" name="total_pembayaran"
                                                            value="{{ $transaksiMasuk->total_pembayaran }}"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="Lunas"
                                                                {{ $transaksiMasuk->status == 'Lunas' ? 'selected' : '' }}>
                                                                Lunas</option>
                                                            <option value="Belum Lunas"
                                                                {{ $transaksiMasuk->status == 'Belum Lunas' ? 'selected' : '' }}>
                                                                Belum Lunas</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Bukti Pembayaran (Opsional)</label>
                                                        <input type="file" name="image" class="form-control"
                                                            accept="image/*">
                                                        @if ($transaksiMasuk->image)
                                                            <small class="text-muted d-block mt-1">Bukti lama: <a
                                                                    href="{{ asset('storage/' . $transaksiMasuk->image) }}"
                                                                    target="_blank">Lihat</a></small>
                                                        @endif
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Catatan</label>
                                                        <textarea name="catatan" class="form-control" rows="3">{{ $transaksiMasuk->catatan }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer mt-3">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                {{-- END TABEL --}}
            </div>
        </div>

    </div>
@endsection

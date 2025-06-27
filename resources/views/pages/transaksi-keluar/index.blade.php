@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        {{-- JUDUL --}}
        <h1 class="mt-4">Daftar Transaksi Keluar</h1>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Daftar Transaksi Keluar</span>
                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal"
                    data-bs-target="#transaksiKeluarModal">
                    Tambah Transaksi Keluar
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
                            <th>Acara</th>
                            <th>Total Pembayaran</th>
                            <th>Tanggal & Waktu</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarTransaksiKeluar as $transaksiKeluar)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a
                                        href="{{ route('acara.show', $transaksiKeluar->acara->id) }}">{{ $transaksiKeluar->acara->nama_acara }}</a>
                                </td>
                                <td>Rp. {{ number_format($transaksiKeluar->total_pembayaran, 0, ',', '.') }}</td>
                                <td>
                                    {{ Carbon\Carbon::parse($transaksiKeluar->created_at)->translatedFormat('d F Y H:i:s') }}
                                </td>
                                <td>{{ $transaksiKeluar->catatan ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('transaksi-keluar.edit', $transaksiKeluar->id) }}"
                                            class="btn btn-sm btn-warning d-flex align-items-center gap-1">
                                            <i class='bx  bxs-pencil'></i>
                                            Edit
                                        </a>
                                        <form
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi keluar?');"
                                            action="{{ route('transaksi-keluar.destroy', $transaksiKeluar->id) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                                <i class='bx  bxs-trash'></i>
                                                Hapus
                                            </button>
                                        </form>
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

    <!-- Modal Tambah Transaksi Keluar -->
    <div class="modal fade" id="transaksiKeluarModal" tabindex="-1" aria-labelledby="transaksiKeluarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('transaksi-keluar.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="acaraModalLabel">Tambah Transaksi Keluar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            {{-- Acara --}}
                            <div class="mb-3 col-12">
                                <label for="acara_id" class="form-label">Acara</label>
                                <select required name="acara_id" id="acara_id" class="form-control">
                                    <option selected disabled value="">-- Pilih Acara --</option>
                                    @foreach ($daftarAcara as $acara)
                                        <option value="{{ $acara->id }}">
                                            {{ $acara->nama_acara }} |
                                            Rp. {{ number_format($acara->harga, 0, ',', '.') }} |
                                            {{ $acara->tanggal_mulai }}
                                            s.d.
                                            {{ $acara->tanggal_selesai }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Total Pembayaran --}}
                            <x-text-field.currency class="mb-3" name="total_pembayaran" label="Total Pembayaran"
                                placeholder="Total pembayaran" />

                            {{-- Catatan --}}
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

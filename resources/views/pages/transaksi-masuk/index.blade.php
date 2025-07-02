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
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi masuk?');"
                                        action="{{ route('transaksi-masuk.destroy', $transaksiMasuk->id) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                            <i class='bx  bxs-trash'></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- END TABEL --}}
            </div>
        </div>

    </div>
@endsection

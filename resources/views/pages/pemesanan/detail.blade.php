@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Detail Pemesanan</h1>

        <div class="card-body card mt-4">

            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>

            <div class="row">
                {{-- Nama Pemesan --}}
                <div class="mb-3 col-md-6">
                    <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                    <input disabled value="{{ $pemesanan->nama_pemesan }}" type="text" name="nama_pemesan"
                        id="nama_pemesan" class="form-control" placeholder="Nama pemesan" required>
                </div>
                {{-- Nama Pemesan --}}
                <div class="mb-3 col-md-6">
                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                    <input disabled value="{{ $pemesanan->nomor_telepon }}" type="text" name="nomor_telepon"
                        id="nomor_telepon" class="form-control" placeholder="Nomor Telepon" required>
                </div>
                {{-- Acara --}}
                <div class="mb-3 col-12">
                    <label for="acara_id" class="form-label">Acara</label>
                    <select disabled required name="acara_id" id="acara_id" class="form-control">
                        <option selected disabled value="">-- Pilih Acara --</option>
                        <option>
                            {{ $pemesanan->acara->nama_acara }} -
                            {{ \Carbon\Carbon::parse($pemesanan->acara->tanggal_mulai)->translatedFormat('d F Y') }}
                            s.d.
                            {{ \Carbon\Carbon::parse($pemesanan->acara->tanggal_selesai)->translatedFormat('d F Y') }}
                        </option>
                    </select>
                </div>
            </div>
            {{-- Catatan --}}
            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                <textarea disabled name="catatan" id="catatan" class="form-control" rows="3"
                    placeholder="Tambahkan catatan jika perlu">{{ $pemesanan->catatan }}</textarea>
            </div>

            <div class="mb-3 d-flex gap-4">
                {{-- Status --}}
                <div>
                    <label class="form-label">Status: </label>
                    @if ($pemesanan->getSisaPembayaran() <= 0)
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-danger">Belum Lunas</span>
                    @endif
                </div>
                <div>
                    {{-- Sisa Bayar --}}
                    <label class="form-label">Sisa Bayar: </label>
                    <strong>
                        Rp.
                        {{ number_format($pemesanan->getSisaPembayaran(), 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            @if ($pemesanan->transaksiMasuk->isNotEmpty())
                <h3 class="mb-3">Daftar Transaksi Masuk</h3>
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
                                    <strong>{{ number_format($transaksiMasuk->total_pembayaran, '0', ',', '.') }}</strong>
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
            @endif

        </div>
    </div>
@endsection

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
                {{-- Nama Pemesan --}}
                <div class="mb-3">
                    <label class="form-label">Nama Acara</label>
                    <input disabled value="{{ $pemesanan->acara->nama_acara }}" class="form-control" equired>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" id="jam_mulai" class="form-control" value="{{ $pemesanan->jam_mulai }}" readonly disabled>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" id="jam_selesai" class="form-control" value="{{ $pemesanan->jam_selesai }}"
                        readonly disabled>
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
                <div>
                    {{-- Total Sudah Dibayar --}}
                    <label class="form-label">Total Sudah Dibayar: </label>
                    <strong>
                        Rp.
                        {{ number_format($pemesanan->getTotalSudahBayar(), 0, ',', '.') }}
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
                            <th>Bukti Pembayaran</th>
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
                                <td>
                                    @if ($transaksiMasuk->image)
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#imgModal{{ $transaksiMasuk->id }}">
                                            <img src="{{ asset('storage/' . $transaksiMasuk->image) }}"
                                                alt="Bukti Pembayaran" width="100" class="rounded shadow-sm"
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

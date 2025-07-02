@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Acara</h1>

        <main class="card-body card mt-4">

            <div class="d-flex gap-2 mb-2">
                <a href="{{ route('acara.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

            <div class="modal-body row">
                {{-- Nama Acara --}}
                <div class="mb-3 col-md-6">
                    <label for="nama_acara" class="form-label">Nama Acara</label>
                    <input disabled value="{{ $acara->nama_acara }}" type="text" name="nama_acara" id="nama_acara"
                        class="form-control" placeholder="Nama acara" required>
                </div>
                {{-- Lokasi --}}
                <div class="mb-3 col-md-6">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input disabled value="{{ $acara->lokasi }}" type="text" name="lokasi" id="lokasi"
                        class="form-control" placeholder="Lokasi" required>
                </div>
                {{-- Harga --}}
                <div class="mb-3 col-md-6">
                    <label for="harga" class="form-label">Harga</label>
                    <input disabled value="{{ $acara->harga }}" type="text" name="harga" id="harga"
                        class="form-control" placeholder="Harga" required>
                </div>
                {{-- Tanggal Mulai --}}
                <div class="mb-3 col-md-6">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input disabled value="{{ $acara->tanggal_mulai }}" type="date" name="tanggal_mulai"
                        id="tanggal_mulai" class="form-control" required>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input disabled value="{{ $acara->tanggal_selesai }}" type="date" name="tanggal_selesai"
                        id="tanggal_selesai" class="form-control" required>
                </div>

                {{-- Catatan --}}
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea disabled name="catatan" id="catatan" class="form-control" rows="3"
                        placeholder="Tambahkan catatan jika perlu">{{ $acara->catatan }}</textarea>
                </div>

            </div>


            <div class="mb-3 d-flex gap-4 flex-wrap">
                {{-- Total Pesanan --}}
                {{-- <div>
                    <label for="catatan" class="form-label">Total Pemesanan: </label>
                    <span class="badge bg-primary">{{ $acara->pemesanan->count() }} Peserta</span>
                </div> --}}
                <div>
                    <label for="catatan" class="form-label">Transaksi Masuk: </label>
                    <strong>
                        Rp. {{ number_format($acara->transaksiMasuk->sum('total_pembayaran'), '0', ',', '.') }}
                    </strong>
                </div>
                <div>
                    <label for="catatan" class="form-label">Transaksi Keluar: </label>
                    <strong>
                        Rp. {{ number_format($acara->transaksiKeluar->sum('total_pembayaran'), '0', ',', '.') }}
                    </strong>
                </div>
                <div>
                    <label for="catatan" class="form-label">Pendapatan: </label>
                    <strong>
                        Rp.
                        {{ number_format($acara->transaksiMasuk->sum('total_pembayaran') - $acara->transaksiKeluar->sum('total_pembayaran'), '0', ',', '.') }}
                    </strong>
                </div>
            </div>

            <ul class="nav nav-fill nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="fill-tab-0" data-bs-toggle="tab" href="#fill-tabpanel-0" role="tab"
                        aria-controls="fill-tabpanel-0" aria-selected="true">Pemesan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="fill-tab-1" data-bs-toggle="tab" href="#fill-tabpanel-1" role="tab"
                        aria-controls="fill-tabpanel-1" aria-selected="false">Daftar Transaksi Masuk</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="fill-tab-2" data-bs-toggle="tab" href="#fill-tabpanel-2" role="tab"
                        aria-controls="fill-tabpanel-2" aria-selected="false">Daftar Transaksi Keluar</a>
                </li>
            </ul>
            <div class="tab-content pt-5" id="tab-content">
                <div class="tab-pane active" id="fill-tabpanel-0" role="tabpanel" aria-labelledby="fill-tab-0">

                    {{-- TABEL --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pemesan</th>
                                <th>Nomor Telepon</th>
                                <th>Acara</th>
                                <th>Status Bayar</th>
                                <th>Sisa Bayar</th>
                                <th>Catatan</th>
                                <th>Tanggal & Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarPemesanan as $pemesanan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pemesanan->nama_pemesan }}</td>
                                    <td>{{ $pemesanan->nomor_telepon }}</td>
                                    <td>
                                        <a href="{{ route('acara.show', $pemesanan->acara->id) }}">
                                            {{ $pemesanan->acara->nama_acara }}
                                        </a>
                                    </td>
                                    <td>
                                        {{-- Jika pembayaran lunas --}}
                                        @if ($pemesanan->getSisaPembayaran() <= 0)
                                            <span class="badge bg-success">Lunas</span>
                                            {{-- Jika belum lunas --}}
                                        @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>
                                            Rp.
                                            {{ number_format($pemesanan->getSisaPembayaran(), 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>{{ $pemesanan->catatan ?? '-' }}</td>
                                    <td>
                                        {{ Carbon\Carbon::parse($pemesanan->created_at)->translatedFormat('d F Y H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if ($pemesanan->getSisaPembayaran() > 0)
                                                <button data-bs-toggle="modal" data-bs-target="#bayarSisaModal"
                                                    class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                    onclick="
                                            bayarSisa(
                                            {{ $pemesanan->id }}, 
                                            '{{ $pemesanan->nama_pemesan }}', 
                                            {{ $pemesanan->getTotalSudahBayar() }},
                                             {{ $pemesanan->acara->harga }},
                                             {{ $pemesanan->getSisaPembayaran() }}
                                             )">
                                                    {{-- Ketika tombol sisa bayar diklik menuju ke script fungsi bayarSisa() --}}
                                                    <i class='bx  bxs-wallet-note'></i>
                                                    Bayar Sisa
                                                </button>
                                            @endif
                                            <a href="{{ route('pemesanan.show', $pemesanan->id) }}"
                                                class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                                                <i class='bx  bxs-info-circle'></i>
                                                Detail
                                            </a>
                                            <a href="{{ route('pemesanan.edit', $pemesanan->id) }}"
                                                class="btn btn-sm btn-warning d-flex align-items-center gap-1">
                                                <i class='bx  bxs-pencil'></i>
                                                Edit
                                            </a>
                                            <form
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemesanan {{ $pemesanan->nama_pemesanan }}?');"
                                                action="{{ route('pemesanan.destroy', $pemesanan->id) }}" method="post">
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
                <div class="tab-pane" id="fill-tabpanel-1" role="tabpanel" aria-labelledby="fill-tab-1">

                    {{-- TABEL --}}
                    <table class="table table-bordered">
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
                                        <form
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi masuk?');"
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
                <div class="tab-pane" id="fill-tabpanel-2" role="tabpanel" aria-labelledby="fill-tab-2">

                    {{-- TABEL --}}
                    <table class="table table-bordered">
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

        </main>
    </div>
@endsection

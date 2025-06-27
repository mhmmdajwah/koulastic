@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Daftar Pemesanan</h1>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Daftar Pemesanan</span>
                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal"
                    data-bs-target="#pemesananModal">
                    Tambah Pemesanan
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
        </div>
    </div>

    <!-- Modal Tambah Pemesanan -->
    <div class="modal fade" id="pemesananModal" tabindex="-1" aria-labelledby="pemesananModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pemesanan.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="acaraModalLabel">Tambah Pemesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- Nama Pemesan --}}
                            <div class="mb-3 col-md-6">
                                <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                                <input value="Raju Adha Dani" type="text" name="nama_pemesan" id="nama_pemesan"
                                    class="form-control" placeholder="Nama pemesan" required>
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="mb-3 col-md-6">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input value="08123456789" type="text" name="nomor_telepon" id="nomor_telepon"
                                    class="form-control" placeholder="Nomor telepon" required>
                            </div>

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
                            <x-text-field.currency class="mb-3 col-6" name="total_pembayaran" label="Total Pembayaran"
                                placeholder="Total pembayaran" />

                            {{-- Metode Pembayaran --}}
                            <div class="mb-3 col-6">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <select required name="metode_pembayaran" id="metode_pembayaran" class="form-control">
                                    <option value="" selected disabled>-- Pilih Metode Pembayaran --</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Transfer">Transfer Bank</option>
                                    <option value="Qris">QRIS</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3"
                                    placeholder="Tambahkan catatan jika perlu"></textarea>
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

    <!-- Modal Bayar Sisa -->
    <div class="modal fade" id="bayarSisaModal" tabindex="-1" aria-labelledby="bayarSisaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pemesanan.sisa-bayar', 1) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="acaraModalLabel">Bayar Sisa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-2 mb-3">
                            <div class="d-flex flex-column">
                                <span>Nama Pemesan</span>
                                <span>Sudah Bayar</span>
                                <span>Total Harga Acara</span>
                                <span>Sisa Bayar</span>
                            </div>
                            <div class="d-flex flex-column">
                                <strong>: <span id="namaPemesanText"></span></strong>
                                <strong>: Rp. <span id="sudahBayarText"></span></strong>
                                <strong>: Rp. <span id="totalHargaText"></span></strong>
                                <strong class="text-danger">: Rp. <span id="sisaBayarText"></span></strong>
                            </div>
                        </div>

                        {{-- Pemesanan ID --}}
                        <input id="pemesananIdSisaBayar" type="hidden" name="pemesanan_id" value="1">

                        {{-- Total Pembayaran --}}
                        <x-text-field.currency class="mb-3" name="total_pembayaran_sisa" label="Total Pembayaran"
                            placeholder="Total pembayaran Sisa" />

                        {{-- Metode Pembayaran --}}
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select required name="metode_pembayaran" id="metode_pembayaran" class="form-control">
                                <option value="" selected disabled>-- Pilih Metode Pembayaran --</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer Bank</option>
                                <option value="Qris">QRIS</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                        </div>
                        {{-- Catatan --}}
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3"
                                placeholder="Tambahkan catatan jika perlu"></textarea>
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

    <script>
        function formatRupiah(number) {
            return number.toLocaleString('id-ID');
        }

        function bayarSisa(id, nama, totalSudahBayar, totalHargaAcara, sisaBayar) {
            document.getElementById('pemesananIdSisaBayar').value = id;
            document.getElementById('namaPemesanText').textContent = nama;
            document.getElementById('sudahBayarText').textContent = formatRupiah(totalSudahBayar);
            document.getElementById('totalHargaText').textContent = formatRupiah(totalHargaAcara);
            document.getElementById('sisaBayarText').textContent = formatRupiah(sisaBayar);
        }
    </script>


@endsection

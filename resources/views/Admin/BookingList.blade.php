@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">List Booking</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Booking</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Data Booking</span>
                <!-- Tombol untuk memunculkan modal -->
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal">Tambah
                    Booking</button>
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
                            <th>Nama Klien</th>
                            <th>Tanggal Acara</th>
                            <th>Status Bayar</th>
                            <th>Sisa Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $item)
                            <tr>
                                <td>{{ $item->client_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->event_date)->format('d M Y') }}</td>
                                <td>
                                    <span
                                        class="badge 
                        @if ($item->payment_status == 'lunas') bg-success 
                        @else bg-danger @endif">
                                        {{ ucfirst($item->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->payment_balance == 0)
                                        <span class="text-success">Rp 0</span>
                                    @elseif ($item->payment_balance > 0)
                                        <span class="text-danger">Kurang Rp
                                            -{{ number_format($item->payment_balance, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-danger">Kurang Rp
                                            {{ number_format($item->payment_balance, 0, ',', '.') }}</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#modalBayar{{ $item->id }}">Bayar Sisa</a>
                                    <a href="{{ route('booking.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <a href="{{ route('booking.destroy', $item->id) }}" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalBayar{{ $item->id }}" tabindex="-1"
                                aria-labelledby="bayarLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('booking.bayar_sisa', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="bayarLabel{{ $item->id }}">Bayar Sisa
                                                    Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Nama Klien: <strong>{{ $item->client_name }}</strong></p>
                                                <p>Total Harga: <strong>Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</strong></p>
                                                <p>Sudah DP: <strong>Rp
                                                        {{ number_format($item->down_payment, 0, ',', '.') }}</strong></p>
                                                <p class="text-danger">Sisa: Rp
                                                    {{ number_format($item->price - $item->down_payment, 0, ',', '.') }}
                                                </p>

                                                <div class="mb-3">
                                                    <label for="payment_amount" class="form-label">Jumlah Pembayaran</label>
                                                    <input type="number" name="payment_amount" class="form-control"
                                                        min="1" max="{{ $item->price - $item->down_payment }}"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                                    <select name="payment_method" class="form-control" required>
                                                        <option value="">-- Pilih --</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="transfer">Transfer Bank</option>
                                                        <option value="qris">QRIS</option>
                                                        <option value="ewallet">E-Wallet</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                                    <input type="date" name="payment_date" class="form-control"
                                                        value="{{ date('Y-m-d') }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="notes" class="form-label">Catatan (opsional)</label>
                                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Bayar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Booking -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Tambah Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="client_name" class="form-label">Nama Klien</label>
                                <input type="text" name="client_name" id="client_name" class="form-control" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="phone_number" class="form-label">No. HP</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="event_name" class="form-label">Nama Acara</label>
                                <input type="text" name="event_name" id="event_name" class="form-control" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="event_date" class="form-label">Tanggal Acara</label>
                                <input type="date" name="event_date" id="event_date" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" name="location" id="location" class="form-control" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="price" class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" id="price" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="down_payment" class="form-label">DP (Rp)</label>
                                <input type="number" name="down_payment" id="down_payment" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                    <option value="ewallet">E-Wallet</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="payment_status" class="form-label">Status Pembayaran</label>
                                <select name="payment_status" id="payment_status" class="form-control">
                                    <option value="belum lunas">Belum Lunas</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="status" class="form-label">Status Booking</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="payment_balance" class="form-label">Sisa Pembayaran (Rp)</label>
                                <input type="number" name="payment_balance" id="payment_balance" class="form-control"
                                    readonly>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

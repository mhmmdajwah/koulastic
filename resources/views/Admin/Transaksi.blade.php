@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">List Keungan Masuk</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Keungan Masuk</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-1"></i> Data Transaksi Masuk</span>
                <!-- Tombol untuk memunculkan modal -->
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#transactionModal">Tambah
                    Transaksi</button>
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
                            <th>Nama Acara</th>
                            <th>Tanggal Acara</th>
                            <th>Jumlah</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $item)
                            <tr>
                                <td>{{ $item->booking->client_name ?? '-' }}</td>
                                <td>{{ $item->booking->event_name ?? '-' }}</td>
                                <td>{{ $item->booking->event_date ? \Carbon\Carbon::parse($item->booking->event_date)->format('d M Y') : '-' }}
                                </td>
                                <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($item->payment_method) }}</td>
                                <td>
                                    <span
                                        class="badge
                        @if ($item->status == 'paid') bg-success
                        @elseif ($item->status == 'unpaid') bg-danger
                        @else bg-secondary @endif
                    ">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->payment_date ? \Carbon\Carbon::parse($item->payment_date)->format('d M Y') : '-' }}
                                </td>
                                <td>{{ $item->note ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('transaksi.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('transaksi.delete', $item->id) }}" method="POST"
                                        style="display:inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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

    <!-- Modal Tambah Transaction -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionModalLabel">Tambah Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="booking_id" class="form-label">Pilih Booking</label>
                                <select name="booking_id" id="booking_id" class="form-control" required>
                                    <option value="" selected disabled>-- Pilih Booking --</option>
                                    @foreach ($bookings as $booking)
                                        <option value="{{ $booking->id }}">
                                            {{ $booking->client_name }} - {{ $booking->event_name }}
                                            ({{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="amount" class="form-label">Jumlah Pembayaran</label>
                                <input type="number" name="amount" id="amount" class="form-control" min="0"
                                    step="1000" placeholder="Masukkan jumlah pembayaran">
                            </div>

                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value="" selected disabled>-- Pilih Metode --</option>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="debit_card">Debit Card</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status Pembayaran</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="belum lunas">Belum Lunas</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="note" class="form-label">Catatan (Opsional)</label>
                                <textarea name="note" id="note" class="form-control" rows="3"
                                    placeholder="Masukkan catatan jika ada"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    const bookingData = @json($bookingData);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookingSelect = document.getElementById('booking_id');
        const amountInput = document.getElementById('amount');
        const methodSelect = document.getElementById('payment_method');
        const statusSelect = document.getElementById('status');
        const dateInput = document.getElementById('payment_date');

        if (bookingSelect) {
            bookingSelect.addEventListener('change', function() {
                const bookingId = this.value;
                const data = bookingData[bookingId];

                if (data) {
                    amountInput.value = data.amount ?? '';
                    methodSelect.value = data.payment_method ?? '';
                    statusSelect.value = data.status ?? '';
                    dateInput.value = data.payment_date ?? '';
                } else {
                    amountInput.value = '';
                    methodSelect.value = '';
                    statusSelect.value = '';
                    dateInput.value = '';
                }
            });
        }
    });
</script>

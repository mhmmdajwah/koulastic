@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Transaksi</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('transaksi.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Kolom kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="booking_id" class="form-label">Pilih Booking</label>
                                <select name="booking_id" id="booking_id" class="form-control" required>
                                    <option value="" disabled>-- Pilih Booking --</option>
                                    @foreach ($bookings as $booking)
                                        <option value="{{ $booking->id }}" {{ $transaction->booking_id == $booking->id ? 'selected' : '' }}>
                                            {{ $booking->client_name }} - {{ $booking->event_name }}
                                            ({{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Jumlah Pembayaran</label>
                                <input type="number" name="amount" id="amount" class="form-control"
                                    value="{{ $transaction->amount }}" required min="0" step="1000"
                                    placeholder="Masukkan jumlah pembayaran">
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="" disabled>-- Pilih Metode --</option>
                                    <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="transfer" {{ $transaction->payment_method == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="credit_card" {{ $transaction->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ $transaction->payment_method == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kolom kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status Pembayaran</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="unpaid" {{ $transaction->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($transaction->payment_date)->format('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Catatan (Opsional)</label>
                                <textarea name="note" id="note" class="form-control" rows="6" placeholder="Masukkan catatan jika ada">{{ $transaction->note }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui Transaksi</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection

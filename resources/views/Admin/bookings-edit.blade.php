@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Booking</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('booking.index') }}">Booking</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="client_name" class="form-label">Nama Klien</label>
                            <input type="text" name="client_name" id="client_name" class="form-control"
                                value="{{ $booking->client_name }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="phone_number" class="form-label">No. HP</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control"
                                value="{{ $booking->phone_number }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="event_name" class="form-label">Nama Acara</label>
                            <input type="text" name="event_name" id="event_name" class="form-control"
                                value="{{ $booking->event_name }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="event_date" class="form-label">Tanggal Acara</label>
                            <input type="date" name="event_date" id="event_date" class="form-control"
                                value="{{ $booking->event_date }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" name="location" id="location" class="form-control"
                                value="{{ $booking->location }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <input type="number" name="price" id="price" class="form-control"
                                value="{{ $booking->price }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="down_payment" class="form-label">DP (Rp)</label>
                            <input type="number" name="down_payment" id="down_payment" class="form-control"
                                value="{{ $booking->down_payment }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="cash" {{ $booking->payment_method == 'cash' ? 'selected' : '' }}>Cash
                                </option>
                                <option value="transfer" {{ $booking->payment_method == 'transfer' ? 'selected' : '' }}>
                                    Transfer Bank</option>
                                <option value="qris" {{ $booking->payment_method == 'qris' ? 'selected' : '' }}>QRIS
                                </option>
                                <option value="ewallet" {{ $booking->payment_method == 'ewallet' ? 'selected' : '' }}>
                                    E-Wallet</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control"
                                value="{{ $booking->payment_date }}">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="payment_status" class="form-label">Status Pembayaran</label>
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="belum lunas"
                                    {{ $booking->payment_status == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="lunas" {{ $booking->payment_status == 'lunas' ? 'selected' : '' }}>Lunas
                                </option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status Booking</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="payment_balance" class="form-label">Sisa Pembayaran (Rp)</label>
                            <input type="number" name="payment_balance" id="payment_balance" class="form-control"
                                value="{{ $booking->payment_balance }}" readonly>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ $booking->notes }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui Booking</button>
                    <a href="{{ route('booking.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>

    </div>
@endsection

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
                    <div class="mb-3">
                        <label for="client_name" class="form-label">Nama Klien</label>
                        <input type="text" name="client_name" id="client_name" class="form-control" value="{{ $booking->client_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="event_name" class="form-label">Nama Acara</label>
                        <input type="text" name="event_name" id="event_name" class="form-control" value="{{ $booking->event_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="event_date" class="form-label">Tanggal Acara</label>
                        <input type="date" name="event_date" id="event_date" class="form-control" value="{{ $booking->event_date }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ $booking->location }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui Booking</button>
                    <a href="{{ route('booking.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection

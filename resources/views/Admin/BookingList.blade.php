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
                            <th>Nama Acara</th>
                            <th>Tanggal Acara</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $item)
                            <tr>
                                <td>{{ $item->client_name }}</td>
                                <td>{{ $item->event_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->event_date)->format('d M Y') }}</td>
                                <td>{{ $item->location }}</td>
                                <td>
                                    <span
                                        class="badge 
                                    @if ($item->status == 'pending') bg-warning 
                                    @elseif($item->status == 'confirmed') bg-info 
                                    @elseif($item->status == 'completed') bg-success 
                                    @else bg-secondary @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('booking.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="{{ route('booking.destroy', $item->id) }}" class="btn btn-sm btn-danger"
                                        onclick="confirm('apakaah anda yakin ingin mengahpus data')">Hapus</a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Booking -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Tambah Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Nama Klien</label>
                            <input type="text" name="client_name" id="client_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="event_name" class="form-label">Nama Acara</label>
                            <input type="text" name="event_name" id="event_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="event_date" class="form-label">Tanggal Acara</label>
                            <input type="date" name="event_date" id="event_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" name="location" id="location" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                            </select>
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

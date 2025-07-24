@extends('layouts.app')

<style>
    #calendar {
    background: rgb(28, 173, 137);
    padding: 15px;
    border-radius: 10px;
    min-height: 600px;
}
</style>

@section('content')
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                background-image: url('img/bg-koulastic.jpg'); 
                background-size: cover; 
                background-position: center; 
                background-repeat: no-repeat; 
                z-index: -1;">
    </div>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <div class="row">
            <!-- Total Pemesanan -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Total Pemesanan: <strong>{{ App\Models\Pemesanan::count() }}</strong></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('pemesanan.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Total Acara -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Total Acara: <strong>{{ App\Models\Acara::count() }}</strong></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('acara.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Masuk -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Total Transaksi Masuk:
                        <strong>Rp. {{ number_format(App\Models\TransaksiMasuk::sum('total_pembayaran'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('transaksi-masuk.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Keluar -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-black mb-4">
                    <div class="card-body">Total Transaksi Keluar:
                        <strong>Rp. {{ number_format(App\Models\TransaksiKeluar::sum('total_pembayaran'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-black stretched-link" href="{{ route('transaksi-keluar.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Keuntungan -->
@php
    $totalMasuk = App\Models\TransaksiMasuk::sum('total_pembayaran');
    $totalKeluar = App\Models\TransaksiKeluar::sum('total_pembayaran');
    $keuntungan = $totalMasuk - $totalKeluar;
@endphp

<div class="col-xl-3 col-md-6">
    <div class="card bg-success text-white mb-4">
        <div class="card-body">
            Total Keuntungan: 
            <strong>Rp. {{ number_format($keuntungan, 0, ',', '.') }}</strong>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
            <span class="small text-white">Bersih dari selisih transaksi</span>
            <div class="small text-white"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
</div>


<!-- Kalender Booking -->
<div class="container-fluid px-4">
    <h2 class="mt-4 text-black">Dashboard Kalender Booking</h2>
   <div id="calendar" style="background: rgb(28, 173, 137); padding: 15px; border-radius: 10px; min-height: 600px;"></div>

</div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Tambahkan jika belum ada FullCalendar -->
{{-- <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet' />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.18/index.global.min.js
"></script> --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listWeek'
            },
            events: {!! json_encode($events) !!},
            eventColor: '#4CAF50'
        });

        calendar.render();
    });
</script>
@endsection
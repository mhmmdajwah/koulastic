@extends('layouts.app')

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f8fafc;
        padding: 40px;
    }

    #calendar {
        max-width: 1000px;
        margin: auto;
        background: white;
        padding: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    .fc-toolbar-title {
        font-size: 1.5rem;
        color: #1e293b;
    }
</style>


@section('content')
    <div
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
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
                        <strong>Rp.
                            {{ number_format(App\Models\TransaksiMasuk::sum('total_pembayaran'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('transaksi-masuk.index') }}">View
                            Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Keluar -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-black mb-4">
                    <div class="card-body">Total Transaksi Keluar:
                        <strong>Rp.
                            {{ number_format(App\Models\TransaksiKeluar::sum('total_pembayaran'), 0, ',', '.') }}</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-black stretched-link" href="{{ route('transaksi-keluar.index') }}">View
                            Details</a>
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
            <h2 style="text-align:center;">Kalender Jadwal Acara</h2>
            <div id='calendar'></div>
        </div>
        <!-- Modal Bootstrap -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTitle">Judul Acara</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Tanggal:</strong> <span id="modalDate"></span></p>
                        <p><strong>Lokasi:</strong> <span id="modalLokasi"></span></p>
                        <p><strong>Catatan:</strong> <span id="modalCatatan"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <h2 style="text-align:center;">Kalender Jadwal Acara</h2>
    <div id='calendar'></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id',
                initialView: 'dayGridMonth',
                events: '{{ route('kalender.acara.json') }}',
                eventClick: function(info) {
                    info.jsEvent.preventDefault();

                    document.getElementById('modalTitle').innerText = info.event.title;
                    document.getElementById('modalDate').innerText = new Date(info.event.start)
                        .toLocaleDateString();
                    document.getElementById('modalLokasi').innerText = info.event.extendedProps
                        .lokasi || '-';
                    document.getElementById('modalCatatan').innerText = info.event.extendedProps
                        .catatan || '-';

                    var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    modal.show();
                }
            });

            calendar.render();
        });
    </script>
@endsection

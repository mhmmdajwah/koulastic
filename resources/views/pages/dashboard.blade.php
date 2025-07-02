@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                {{-- Total Pemesanan --}}
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Total Pemesanan: <strong>{{ App\Models\Pemesanan::count() }}</strong></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('pemesanan.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                {{-- Total Acara --}}
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Total Acara: <strong>{{ App\Models\Acara::count() }}</strong></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('acara.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                {{-- Transaksi Masuk --}}
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Total Transaksi Masuk:
                        <strong>Rp.
                            {{ number_format(App\Models\TransaksiMasuk::sum('total_pembayaran'), 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('transaksi-masuk.index') }}">View
                            Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                {{-- Transaksi Keluar --}}
                <div class="card bg-warning text-black mb-4">
                    <div class="card-body">Total Transaksi Keluar:
                        <strong>Rp.
                            {{ number_format(App\Models\TransaksiKeluar::sum('total_pembayaran'), 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-black stretched-link" href="{{ route('transaksi-keluar.index') }}">View
                            Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

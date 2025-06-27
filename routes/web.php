<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\TransaksiMasukController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login-progress', [AuthController::class, 'progress'])->name('login.progress');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Halaman Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('/pemesanan', PemesananController::class);
    Route::post('/pemesanan/{id}/sisa-bayar/', [PemesananController::class, 'sisaBayar'])
        ->name('pemesanan.sisa-bayar');

    Route::resource('/acara', AcaraController::class);

    Route::resource('/transaksi-masuk', TransaksiMasukController::class);

    Route::resource('/transaksi-keluar', TransaksiKeluarController::class);
});

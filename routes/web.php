<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\TransactionController;
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
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking-store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking-edit/{id}', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking-update/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::get('/booking-destroy/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    Route::put('/booking/bayar-sisa/{id}', [BookingController::class, 'bayarSisa'])->name('booking.bayar_sisa');

    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi-store', [TransactionController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi-edit/{id}', [TransactionController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi-update/{id}', [TransactionController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi-delete/{id}', [TransactionController::class, 'destroy'])->name('transaksi.delete');

    Route::get('/keungan', [FinanceController::class, 'index'])->name('keungan.index');    
    Route::post('/keungan-store', [FinanceController::class, 'store'])->name('keungan.store'); 
    Route::get('/keungan-edit/{id}', [FinanceController::class, 'edit'])->name('keungan.edit');
    Route::put('/keungan-update/{id}', [FinanceController::class, 'update'])->name('keungan.update');
    Route::delete('/keungan-delete/{id}', [FinanceController::class, 'destroy'])->name('keungan.delete');
});
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Acara;

class DashboardController extends Controller
{
    
    public function index()
{
    $pemesanan = Pemesanan::with('acara')->get();

    // Format data untuk FullCalendar
    $events = $pemesanan->filter(function ($item) {
        // Hanya ambil yang punya acara dan tanggal_mulai tidak null
        return $item->acara && $item->acara->tanggal_mulai;
    })->map(function ($item) {
        return [
            'title' => $item->acara->nama_acara ?? 'Acara',
            'start' => $item->acara->tanggal_mulai,
            'url'   => route('pemesanan.show', $item->id),
        ];
    });
//  dd($events); // Cek isi array-nya
    return view('pages.dashboard', [
        'events' => $events,
    ]);
}
}

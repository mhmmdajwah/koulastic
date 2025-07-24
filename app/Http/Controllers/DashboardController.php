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

    public function kalenderJson()
    {
        $events = Acara::all()->map(function ($acara) {
            return [
                'title' => $acara->nama_acara,
                'start' => $acara->tanggal_mulai,
                'end'   => date('Y-m-d', strtotime($acara->tanggal_selesai . ' +1 day')),
                'color' => $acara->status ? '#22c55e' : '#f87171',
                'lokasi' => $acara->lokasi,      
                'catatan' => $acara->catatan,
            ];
        });

        return response()->json($events);
    }
}

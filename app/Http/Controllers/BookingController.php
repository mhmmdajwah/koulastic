<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return view('Admin.BookingList', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'event_name'  => 'required|string|max:255',
            'event_date'  => 'required|date',
            'location'    => 'required|string|max:255',
            'status'      => 'required|in:pending,confirmed,completed',
        ], [
            'client_name.required' => 'Nama klien wajib diisi.',
            'client_name.string'   => 'Nama klien harus berupa teks.',
            'client_name.max'      => 'Nama klien maksimal 255 karakter.',

            'event_name.required'  => 'Nama acara wajib diisi.',
            'event_name.string'    => 'Nama acara harus berupa teks.',
            'event_name.max'       => 'Nama acara maksimal 255 karakter.',

            'event_date.required'  => 'Tanggal acara wajib diisi.',
            'event_date.date'      => 'Format tanggal acara tidak valid.',

            'location.required'    => 'Lokasi acara wajib diisi.',
            'location.string'      => 'Lokasi harus berupa teks.',
            'location.max'         => 'Lokasi maksimal 255 karakter.',

            'status.required'      => 'Status acara wajib dipilih.',
            'status.in'            => 'Status harus berupa pending, confirmed, atau completed.',
        ]);


        // Simpan ke database
        Booking::create([
            'client_name' => $request->client_name,
            'event_name'  => $request->event_name,
            'event_date'  => $request->event_date,
            'location'    => $request->location,
            'status'      => $request->status,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('booking.index')->with('success', 'Data booking berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        return view('Admin.bookings-edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'event_name'  => 'required|string|max:255',
            'event_date'  => 'required|date',
            'location'    => 'required|string|max:255',
            'status'      => 'required|in:pending,confirmed,completed',
        ], [
            'client_name.required' => 'Nama klien tidak boleh kosong.',
            'client_name.string'   => 'Nama klien harus berupa teks.',
            'client_name.max'      => 'Nama klien maksimal 255 karakter.',

            'event_name.required'  => 'Nama acara tidak boleh kosong.',
            'event_name.string'    => 'Nama acara harus berupa teks.',
            'event_name.max'       => 'Nama acara maksimal 255 karakter.',

            'event_date.required'  => 'Tanggal acara wajib diisi.',
            'event_date.date'      => 'Format tanggal acara tidak valid.',

            'location.required'    => 'Lokasi acara tidak boleh kosong.',
            'location.string'      => 'Lokasi acara harus berupa teks.',
            'location.max'         => 'Lokasi acara maksimal 255 karakter.',

            'status.required'      => 'Status acara wajib diisi.',
            'status.in'            => 'Status harus berupa pending, confirmed, atau completed.',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'client_name' => $request->client_name,
            'event_name'  => $request->event_name,
            'event_date'  => $request->event_date,
            'location'    => $request->location,
            'status'      => $request->status,
        ]);

        return redirect()->route('booking.index')->with('success', 'Data booking berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dihapus.');
    }
}

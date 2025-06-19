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
            'client_name'      => 'required|string|max:255',
            'phone_number'     => 'required|string|max:20',
            'event_name'       => 'required|string|max:255',
            'event_date'       => 'required|date',
            'location'         => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'down_payment'     => 'required|numeric|min:0',
            'payment_method'   => 'nullable|string|max:100',
            'payment_date'     => 'nullable|date',
            'notes'            => 'nullable|string',
            'booking_detail'   => 'nullable|string',
            'payment_status'   => 'required|in:lunas,belum lunas',
            'status'           => 'required|in:pending,confirmed,completed',
        ], [
            'client_name.required'     => 'Nama klien wajib diisi.',
            'phone_number.required'    => 'No. HP wajib diisi.',
            'event_name.required'      => 'Nama acara wajib diisi.',
            'event_date.required'      => 'Tanggal acara wajib diisi.',
            'location.required'        => 'Lokasi acara wajib diisi.',
            'price.required'           => 'Harga wajib diisi.',
            'down_payment.required'    => 'DP wajib diisi.',
            'payment_status.required'  => 'Status pembayaran wajib dipilih.',
            'status.required'          => 'Status booking wajib dipilih.',
        ]);

        $payment_balance = $request->down_payment - $request->price;

        Booking::create([
            'client_name'     => $request->client_name,
            'phone_number'    => $request->phone_number,
            'event_name'      => $request->event_name,
            'event_date'      => $request->event_date,
            'location'        => $request->location,
            'price'           => $request->price,
            'down_payment'    => $request->down_payment,
            'payment_method'  => $request->payment_method,
            'payment_date'    => $request->payment_date,
            'notes'           => $request->notes,
            'booking_detail'  => $request->booking_detail,
            'payment_status'  => $request->payment_status,
            'payment_balance' => $payment_balance,
            'status'          => $request->status,
        ]);

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

    public function bayarSisa(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'payment_amount'  => 'required|numeric|min:1',
            'payment_method'  => 'required|string',
            'payment_date'    => 'required|date',
        ]);

        // Tambah DP
        $booking->down_payment += $request->payment_amount;

        // Update status dan sisa bayar
        $booking->payment_status = $booking->down_payment >= $booking->price ? 'lunas' : 'belum lunas';
        $booking->payment_balance = $booking->price - $booking->down_payment;
        $booking->payment_method = $request->payment_method;
        $booking->payment_date = $request->payment_date;
        $booking->notes = $request->notes;

        $booking->save();

        return redirect()->route('booking.index')->with('success', 'Pembayaran sisa berhasil dicatat.');
    }
}

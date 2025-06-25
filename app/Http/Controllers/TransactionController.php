<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $usedBookingIds = Transaction::pluck('booking_id');

        $bookings = Booking::whereNotIn('id', $usedBookingIds)->get();

        $transaksi = Transaction::with('booking')->get();

        $bookingData = $bookings->mapWithKeys(function ($b) {
            return [
                $b->id => [
                    'amount' => $b->down_payment ?? 0,
                    'payment_method' => $b->payment_method ?? '',
                    'status' => $b->payment_status ?? '',
                    'payment_date' => $b->payment_date ?? '',
                ]
            ];
        });

        return view('Admin.Transaksi', compact('transaksi', 'bookings', 'bookingData'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'booking_id'      => 'required|exists:bookings,id',
            'amount'          => 'required|numeric|min:0',
            'payment_method'  => 'required|string|in:cash,transfer,credit_card,debit_card',
            'status'          => 'required|string',
            'payment_date'    => 'required|date',
            'note'            => 'nullable|string|max:500',
        ], [
            'booking_id.required'     => 'Booking harus dipilih.',
            'booking_id.exists'       => 'Booking yang dipilih tidak valid.',

            'amount.required'         => 'Jumlah pembayaran harus diisi.',
            'amount.numeric'          => 'Jumlah pembayaran harus berupa angka.',
            'amount.min'              => 'Jumlah pembayaran tidak boleh kurang dari 0.',

            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.string'   => 'Metode pembayaran tidak valid.',
            'payment_method.in'       => 'Metode pembayaran harus salah satu dari: Cash, Transfer, Credit Card, atau Debit Card.',

            'status.required'         => 'Status pembayaran harus dipilih.',
            'status.string'           => 'Status pembayaran tidak valid.',

            'payment_date.required'   => 'Tanggal pembayaran harus diisi.',
            'payment_date.date'       => 'Tanggal pembayaran tidak valid.',

            'note.string'             => 'Catatan harus berupa teks.',
            'note.max'                => 'Catatan tidak boleh lebih dari 500 karakter.',
        ]);


        Transaction::create($validatedData);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $bookings = Booking::all();

        return view('Admin.Transactions-edit', compact('transaction', 'bookings'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit_card,debit_card',
            'status' => 'required|in:paid,unpaid',
            'payment_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ], [
            'booking_id.required' => 'Booking harus dipilih.',
            'booking_id.exists' => 'Booking yang dipilih tidak tersedia atau tidak valid.',

            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran tidak boleh kurang dari 0.',

            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran harus salah satu dari: cash, transfer, credit card, atau debit card.',

            'status.required' => 'Status pembayaran wajib dipilih.',
            'status.in' => 'Status pembayaran harus berupa "paid" atau "unpaid".',

            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
            'payment_date.date' => 'Tanggal pembayaran tidak valid.',

            'note.string' => 'Catatan harus berupa teks.',
            'note.max' => 'Catatan tidak boleh lebih dari 1000 karakter.',
        ]);


        $transaction = Transaction::findOrFail($id);

        $transaction->booking_id = $validated['booking_id'];
        $transaction->amount = $validated['amount'];
        $transaction->payment_method = $validated['payment_method'];
        $transaction->status = $validated['status'];
        $transaction->payment_date = $validated['payment_date'];
        $transaction->note = $validated['note'] ?? null;

        $transaction->save();


        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {

        $transaction = Transaction::findOrFail($id);

        // Hapus data transaksi
        $transaction->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}

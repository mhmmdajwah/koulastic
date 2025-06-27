<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;

class TransaksiKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.transaksi-keluar.index', [
            'daftarTransaksiKeluar' => TransaksiKeluar::latest()->get(),
            'daftarAcara' => Acara::where('status', true)->latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'acara_id' => ['required'],
            'total_pembayaran' => ['required'],
            'catatan' => ['nullable'],
        ]);

        TransaksiKeluar::create([
            'acara_id' => $request->acara_id,
            'total_pembayaran' => $request->total_pembayaran,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('transaksi-keluar.index')->with('success', "Transaksi keluar berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.transaksi-keluar.edit', [
            'transaksiKeluar' => TransaksiKeluar::find($id),
            'daftarAcara' => Acara::where('status', true)->latest()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'acara_id' => ['required'],
            'total_pembayaran' => ['required'],
            'catatan' => ['nullable'],
        ]);

        $transaksiKeluar = TransaksiKeluar::find($id);
        $transaksiKeluar->update([
            'acara_id' => $request->acara_id,
            'total_pembayaran' => $request->total_pembayaran,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('transaksi-keluar.index')->with('success', "Transaksi keluar berhasil diubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksiKeluar = TransaksiKeluar::findOrFail($id);
        $transaksiKeluar->delete();

        return redirect()->route('transaksi-keluar.index')->with('success', 'Data transaksi keluar berhasil dihapus.');
    }
}

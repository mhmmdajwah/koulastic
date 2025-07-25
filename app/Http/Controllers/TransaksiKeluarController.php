<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use Illuminate\Http\Request;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\Storage;

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
            'nama_acara' => ['required', 'string', 'max:255'],
            'total_pembayaran' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'max:2048'],
            'catatan' => ['nullable', 'string'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bukti-pembayaran', 'public');
        }

        TransaksiKeluar::create([
            'nama_acara' => $request->nama_acara,
            'total_pembayaran' => $request->total_pembayaran,
            'image' => $imagePath,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi keluar berhasil ditambahkan.');
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
            'nama_acara' => ['required', 'string', 'max:255'],
            'total_pembayaran' => ['required', 'numeric'],
            'catatan' => ['nullable'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $transaksiKeluar = TransaksiKeluar::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($transaksiKeluar->image && Storage::exists('public/' . $transaksiKeluar->image)) {
                Storage::delete('public/' . $transaksiKeluar->image);
            }

            $imagePath = $request->file('image')->store('bukti-pembayaran', 'public');
        } else {
            $imagePath = $transaksiKeluar->image;
        }

        // Update data
        $transaksiKeluar->update([
            'nama_acara' => $request->nama_acara,
            'total_pembayaran' => $request->total_pembayaran,
            'catatan' => $request->catatan,
            'image' => $imagePath,
        ]);

        return redirect()->route('transaksi-keluar.index')
            ->with('success', "Transaksi keluar berhasil diubah.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksiKeluar = TransaksiKeluar::findOrFail($id);

        if ($transaksiKeluar->image && Storage::exists($transaksiKeluar->image)) {
            Storage::delete($transaksiKeluar->image);
        }

        $transaksiKeluar->delete();

        return redirect()->route('transaksi-keluar.index')->with('success', 'Data transaksi keluar berhasil dihapus.');
    }
}

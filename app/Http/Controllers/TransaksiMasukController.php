<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use Illuminate\Support\Facades\Storage;

class TransaksiMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $daftarTransaksiMasuk = TransaksiMasuk::latest()->get();

        return view('pages.transaksi-masuk.index', [
            'daftarTransaksiMasuk' => $daftarTransaksiMasuk
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('pages.acara.detail');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string|max:255',
            'total_pembayaran' => 'required|integer|min:0',
            'status' => 'required|in:Lunas,Belum Lunas',
            'catatan' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
        $transaksi = TransaksiMasuk::findOrFail($id);

        $transaksi->metode_pembayaran = $request->metode_pembayaran;
        $transaksi->total_pembayaran = $request->total_pembayaran;
        $transaksi->status = $request->status;
        $transaksi->catatan = $request->catatan;

        if ($request->hasFile('image')) {
            if ($transaksi->image && Storage::exists('public/' . $transaksi->image)) {
                Storage::delete('public/' . $transaksi->image);
            }
            $path = $request->file('image')->store('bukti_pembayaran', 'public');
            $transaksi->image = $path;
        }

        $transaksi->save();
        return redirect()->route('transaksi-masuk.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksiMasuk = TransaksiMasuk::findOrFail($id);
        $transaksiMasuk->delete();

        if ($transaksiMasuk->pemesanan->getSisaPembayaran() > 0) {
            TransaksiMasuk::where('pemesanan_id', $transaksiMasuk->pemesanan_id)->update([
                'status' => 'Belum Lunas'
            ]);
        }

        return redirect()->route('transaksi-masuk.index')->with('success', 'Data transaksi masuk berhasil dihapus.');
    }
}

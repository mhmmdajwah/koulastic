<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;

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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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

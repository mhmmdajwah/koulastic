<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Pemesanan;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.pemesanan.index', [
            // Mengambil semua data pemesanan berdasarkan data terbaru
            'daftarPemesanan' => Pemesanan::latest()->get(),
            // Hanya mencari acara dengan status aktif/true
            'daftarAcara' => Acara::where('status', true)->latest()->get(),
        ]);
    }

    public function show(string $id)
    {
        $pemesanan = Pemesanan::find($id);

        return view('pages.pemesanan.detail', [
            'pemesanan' => $pemesanan,
            'daftarTransaksiMasuk' => $pemesanan->transaksiMasuk()->latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan' => ['required'],
            'nomor_telepon' => ['nullable'],
            'acara_id' => ['required'],
            'total_pembayaran' => ['required', 'numeric', 'min:500'],
            'metode_pembayaran' => ['required'],
            'catatan' => ['nullable'],
        ]);

        // Menambahkan data pemesanan
        $pemesanan = Pemesanan::create([
            'nama_pemesan' => $request->nama_pemesan,
            'nomor_telepon' => $request->nomor_telepon,
            'acara_id' => $request->acara_id,
            'catatan' => $request->catatan
        ]);

        // Menambahkan data transaksi masuk
        $transaksiMasuk = TransaksiMasuk::create([
            'pemesanan_id' => $pemesanan->id,
            'acara_id' => $pemesanan->acara_id,
            'total_pembayaran' => $request->total_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan' => $request->catatan
        ]);

        if ($pemesanan->getSisaPembayaran() <= 0) {
            $transaksiMasuk->update([
                'status' => 'Lunas'
            ]);
        } else {
            $transaksiMasuk->update([
                'status' => 'Belum Lunas'
            ]);
        }

        return redirect()->route('pemesanan.index')->with('success', "Pemesanan $pemesanan->nama_pemesan berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.pemesanan.edit', [
            'pemesanan' => Pemesanan::findOrFail($id),
            'daftarAcara' => Acara::where('status', true)->latest()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_pemesan' => ['required'],
            'nomor_telepon' => ['nullable'],
            'acara_id' => ['required'],
            'catatan' => ['nullable'],
        ]);

        $pemesanan = Pemesanan::find($id);

        $pemesanan->update([
            'nama_pemesan' => $request->nama_pemesan,
            'nomor_telepon' => $request->nomor_telepon,
            'acara_id' => $request->acara_id,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('pemesanan.index')->with('success', "Pemesanan $request->nama_pemesan berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $namaPemesan = $pemesanan->nama_pemesan;

        $pemesanan->delete();

        return redirect()->route('pemesanan.index')->with('success', "Pemesanan $namaPemesan berhasil dihapus.");
    }

    public function sisaBayar(Request $request, string $id)
    {
        $request->validate([
            'total_pembayaran_sisa' => ['required', 'numeric', 'min:500'],
            'metode_pembayaran' => ['required'],
            'catatan' => ['nullable'],
        ]);

        $pemesanan = Pemesanan::find($id);

        $pemesanan->update([
            'total_pembayaran' => $request->total_pembayaran_sisa + $pemesanan->total_pembayaran
        ]);

        // Menambahkan data transaksi masuk
        $transaksiMasuk = TransaksiMasuk::create([
            'pemesanan_id' => $pemesanan->id,
            'acara_id' => $pemesanan->acara_id,
            'total_pembayaran' => $request->total_pembayaran_sisa,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan' => $request->catatan
        ]);

        if (($pemesanan->getSisaPembayaran() - $request->total_pembayaran_sisa) <= 0) {
            $transaksiMasuk->update([
                'status' => 'Lunas'
            ]);
        } else {
            $transaksiMasuk->update([
                'status' => 'Belum Lunas'
            ]);
        }

        return redirect()->route('pemesanan.index')->with('success', "Pemesanan $pemesanan->nama_pemesan berhasil dibayar.");
    }
}

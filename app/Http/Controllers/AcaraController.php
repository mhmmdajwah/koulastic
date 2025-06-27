<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Pemesanan;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $daftarAcara = Acara::latest()->get();

        return view('pages.acara.index', compact('daftarAcara'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_acara' => ['required'],
            'lokasi' => ['required'],
            'harga' => ['required', 'numeric'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
            'catatan' => ['nullable'],
        ]);

        Acara::create([
            'nama_acara' => $request->nama_acara,
            'lokasi' => $request->lokasi,
            'harga' => $request->harga,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => true, // default acara aktif
            'catatan' => $request->catatan
        ]);

        return redirect()->route('acara.index')->with('success', 'Acara berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $acara = Acara::find($id);

        return view('pages.acara.detail', [
            'acara' => $acara,
            'daftarPemesanan' => $acara->pemesanan()->latest()->get(),
            'daftarTransaksiMasuk' => $acara->transaksiMasuk()->latest()->get(),
            'daftarTransaksiKeluar' => $acara->transaksiKeluar()->latest()->get()
        ]);
    }

    public function edit(string $id)
    {
        $acara = Acara::findOrFail($id);

        return view('pages.acara.edit', compact('acara'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_acara' => ['required'],
            'lokasi' => ['required'],
            'harga' => ['required', 'numeric'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
            'catatan' => ['nullable'],
        ]);

        $acara = Acara::findOrFail($id);

        $acara->update([
            'nama_acara' => $request->nama_acara,
            'lokasi' => $request->lokasi,
            'harga' => $request->harga,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('acara.index')->with('success', "Acara $acara->nama_acara berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $acara = Acara::findOrFail($id);

        // Jika acara tidak memiliki pemesanan
        if ($acara->pemesanan->isEmpty()) {
            // Hapus acara
            $acara->delete();
            return redirect()->route('acara.index')->with('success', 'Acara berhasil dihapus.');
        }
        // Jika acara memiliki pemesanan
        else {
            // Perbarui status
            $acara->update([
                // Jika data status true maka ubah menjadi false
                'status' => !$acara->status
            ]);
            return redirect()->route('acara.index')->with('success', 'Status acara berhasil diubah.');
        }
    }
}

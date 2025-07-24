<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Models\TransaksiMasuk;
use Illuminate\Support\Facades\Storage;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.pemesanan.index', [
            'daftarPemesanan' => Pemesanan::latest()->get(),
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

            'nama_acara' => ['required'],
            'lokasi' => ['required'],
            'harga_acara' => ['required', 'numeric'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],

            'total_pembayaran' => ['required', 'numeric', 'min:500'],
            'metode_pembayaran' => ['required'],
            'catatan' => ['nullable'],
            'image' => ['nullable', 'max:2048'],
        ]);

        // Menambahkan data acara
        $acara = Acara::create([
            'nama_acara' => $request->nama_acara,
            'lokasi' => $request->lokasi,
            'harga' => $request->harga_acara,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'catatan' => $request->catatan
        ]);

        // Menambahkan data pemesanan
        $pemesanan = Pemesanan::create([
            'nama_pemesan' => $request->nama_pemesan,
            'nomor_telepon' => $request->nomor_telepon,
            'acara_id' => $acara->id, // Mengambil acara id yang baru dibuat
            'catatan' => $request->catatan
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('bukti_pembayaran', 'public'); // Simpan di storage/app/public/bukti_pembayaran
        }


        // Menambahkan data transaksi masuk
        $transaksiMasuk = TransaksiMasuk::create([
            'pemesanan_id' => $pemesanan->id, // Mengambil pemesanan id yang baru dibuat
            'acara_id' => $pemesanan->acara_id, // Mengambil acara id melalui relasi pemesanan
            'total_pembayaran' => $request->total_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan' => $request->catatan,
            'image' => $imagePath
        ]);

        // Jika sisa pembayaran kurang dari atau sama dengan 0 (lunas)
        $sisaPembayaran = $request->harga_acara - $request->total_pembayaran;
        if ($sisaPembayaran <= 0) {
            $transaksiMasuk->update([
                'status' => 'Lunas'
            ]);
        }
        // Jika sisa pembayaran lebih dari 0 (belum lunas)
        else {
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


    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_pemesan' => ['required'],
            'nomor_telepon' => ['nullable'],
            'acara_id' => ['required'],
            'catatan' => ['nullable'],
            'image' => ['nullable', 'image', 'max:2048'], // validasi gambar
        ]);

        // Ambil data pemesanan
        $pemesanan = Pemesanan::findOrFail($id);

        // Update data pemesanan
        $pemesanan->update([
            'nama_pemesan' => $request->nama_pemesan,
            'nomor_telepon' => $request->nomor_telepon,
            'acara_id' => $request->acara_id,
            'catatan' => $request->catatan,
        ]);

        // Ambil transaksi pertama yang terkait (karena hasMany)
        $transaksi = $pemesanan->transaksiMasuk()->first();

        if ($transaksi) {
            $dataUpdate = [
                'catatan' => $request->catatan
            ];

            // Jika ada file gambar baru
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($transaksi->image && Storage::disk('public')->exists($transaksi->image)) {
                    Storage::disk('public')->delete($transaksi->image);
                }

                // Simpan gambar baru
                $imagePath = $request->file('image')->store('bukti_pembayaran', 'public');
                $dataUpdate['image'] = $imagePath;
            }

            // Update data transaksi
            $transaksi->update($dataUpdate);
        }

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
        // Validasi form sisa bayar
        $request->validate([
            'total_pembayaran_sisa' => [
                'required', // Wajib ada isi
                'numeric', // Wajib angka
                'min:500' // Minimal angka 500
            ],
            'metode_pembayaran' => ['required'],
            'catatan' => ['nullable'],
            'image' => ['nullable', 'max:2048'],
        ]);

        $pemesanan = Pemesanan::find($id); // Mencari pemesanan berdasarkan id

        // Melakukan update total pembayaran pemesanan
        $pemesanan->update([
            'total_pembayaran' => $request->total_pembayaran_sisa + $pemesanan->total_pembayaran
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('bukti_pembayaran', 'public'); // Simpan di storage/app/public/bukti_pembayaran
        }

        // Menambahkan data transaksi masuk baru
        $transaksiMasuk = TransaksiMasuk::create([
            'pemesanan_id' => $pemesanan->id,
            'acara_id' => $pemesanan->acara_id,
            'total_pembayaran' => $request->total_pembayaran_sisa,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan' => $request->catatan,
            'image' => $imagePath
        ]);

        // Jika hasil pembayaran kurang dari atau sama dengan 0
        if (($pemesanan->getSisaPembayaran() - $request->total_pembayaran_sisa) <= 0) {
            $transaksiMasuk->update([
                'status' => 'Lunas' // Mengubah status transaksi masuk terkait menjadi lunas
            ]);
        }
        // Jika hasil pembayaran lebih dari 0 rupiah (belum lunas karena ada harga yang harus dibayar)
        else {
            $transaksiMasuk->update([
                'status' => 'Belum Lunas' // Mengubah status transaksi masuk terkait menjadi belum lunas
            ]);
        }

        // Melakukan redirect ke halaman pemesanan
        return redirect()->route('pemesanan.index')
            // Dengan mengirimkan pesan sukses
            ->with('success', "Pemesanan $pemesanan->nama_pemesan berhasil dibayar.");
    }
}

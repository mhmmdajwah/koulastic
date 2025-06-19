<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        $keungan = Finance::all();
        return view('Admin.Keungan', compact('keungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_income' => 'required|numeric|min:0',
            'total_expense' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ], [
            'total_income.required' => 'Total pemasukan wajib diisi.',
            'total_income.numeric' => 'Total pemasukan harus berupa angka.',
            'total_income.min' => 'Total pemasukan tidak boleh kurang dari 0.',

            'total_expense.required' => 'Total pengeluaran wajib diisi.',
            'total_expense.numeric' => 'Total pengeluaran harus berupa angka.',
            'total_expense.min' => 'Total pengeluaran tidak boleh kurang dari 0.',

            'notes.string' => 'Catatan harus berupa teks.',
        ]);


        Finance::create($request->all());

        return redirect()->route('keungan.index')->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $finance = Finance::findOrFail($id);
        return view('Admin.Keungan-Edit', compact('finance'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_income' => 'required|numeric|min:0',
            'total_expense' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ], [
            'total_income.required'     => 'Total pemasukan harus diisi.',
            'total_income.numeric'      => 'Total pemasukan harus berupa angka.',
            'total_income.min'          => 'Total pemasukan tidak boleh kurang dari 0.',

            'total_expense.required'    => 'Total pengeluaran harus diisi.',
            'total_expense.numeric'     => 'Total pengeluaran harus berupa angka.',
            'total_expense.min'         => 'Total pengeluaran tidak boleh kurang dari 0.',

            'notes.string'              => 'Catatan harus berupa teks.',
        ]);


        $finance = Finance::findOrFail($id);
        $finance->update($request->all());

        return redirect()->route('keungan.index')->with('success', 'Data keuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $finance = Finance::findOrFail($id);
        $finance->delete();

        return redirect()->route('keungan.index')->with('success', 'Data keuangan berhasil dihapus.');
    }
}

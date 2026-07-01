<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('dashboard.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required', 'location' => 'nullable']);
        Supplier::create($validated);
        return back()->with('success', 'Importir berhasil ditambahkan.');
    }

    public function destroy(Supplier $supplier)
    {
        if (\App\Models\Logbook::where('supplier_id', $supplier->id)->exists() || \App\Models\KapalManifest::where('importir_id', $supplier->id)->exists()) {
            return redirect()->back()->with('error', 'Supplier ini masih digunakan oleh data logbook atau manifest kapal.');
        }
        $supplier->delete();
        return back()->with('success', 'Importir dihapus.');
    }

}

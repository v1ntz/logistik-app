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
        $request->validate(['name' => 'required', 'location' => 'nullable']);
        Supplier::create($request->all());
        return back()->with('success', 'Importir berhasil ditambahkan.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success', 'Importir dihapus.');
    }

}

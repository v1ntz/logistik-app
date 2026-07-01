<?php

namespace App\Http\Controllers;

use App\Models\CattleType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CattleTypeController extends Controller
{
    public function index()
    {
        $cattleTypes = CattleType::latest()->get();
        return view('dashboard.cattle_types.index', compact('cattleTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|unique:cattle_types,name']);
        CattleType::create($validated);
        return back()->with('success', 'Jenis sapi berhasil ditambahkan.');
    }

    public function destroy(CattleType $cattleType)
    {
        if (\App\Models\Logbook::where('cattle_type_id', $cattleType->id)->exists()) {
            return redirect()->back()->with('error', 'Jenis sapi ini masih digunakan oleh data logbook.');
        }
        $cattleType->delete();
        return back()->with('success', 'Jenis sapi dihapus.');
    }

}

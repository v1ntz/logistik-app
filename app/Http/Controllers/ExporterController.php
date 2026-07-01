<?php

namespace App\Http\Controllers;

use App\Models\Exporter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExporterController extends Controller
{
    public function index()
    {
        $exporters = Exporter::latest()->get();
        return view('dashboard.exporters.index', compact('exporters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required', 'location' => 'nullable']);
        Exporter::create($validated);
        return back()->with('success', 'Supplier luar negeri berhasil ditambahkan.');
    }

    public function destroy(Exporter $exporter)
    {
        if (\App\Models\Logbook::where('exporter_id', $exporter->id)->exists()) {
            return redirect()->back()->with('error', 'Eksportir ini masih digunakan oleh data logbook.');
        }
        $exporter->delete();
        return back()->with('success', 'Supplier luar negeri berhasil dihapus.');
    }
}

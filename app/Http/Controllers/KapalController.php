<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\KapalManifest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    public function index()
    {
        $kapals = Kapal::with(['manifests.importir', 'manifests.exporter'])->latest()->get();
        $importirs = Supplier::orderBy('name')->get();
        $exporters = \App\Models\Exporter::orderBy('name')->get();
        return view('dashboard.kapals.index', compact('kapals', 'importirs', 'exporters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kapal' => 'required|string',
            'eta'        => 'nullable|string',
        ]);
        Kapal::create($request->only('nama_kapal', 'eta'));
        return back()->with('success', 'Data kapal berhasil ditambahkan.');
    }

    public function destroy(Kapal $kapal)
    {
        $kapal->delete();
        return back()->with('success', 'Data kapal berhasil dihapus.');
    }

    // Tambah manifest (importir) ke kapal
    public function storeManifest(Request $request, Kapal $kapal)
    {
        $request->validate([
            'importir_id' => 'required|exists:suppliers,id',
            'exporter_id' => 'nullable|exists:exporters,id',
            'kade'        => 'nullable|string',
            'consignee'   => 'nullable|string',
            'party'       => 'nullable|integer|min:1',
        ]);

        $kapal->manifests()->create([
            'importir_id' => $request->importir_id,
            'exporter_id' => $request->exporter_id,
            'kade'        => $request->kade,
            'consignee'   => $request->consignee,
            'party'       => $request->party,
        ]);

        return back()->with('success', 'Manifest importir berhasil ditambahkan ke kapal.');
    }

    // Hapus manifest
    public function destroyManifest(KapalManifest $manifest)
    {
        $manifest->delete();
        return back()->with('success', 'Manifest berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Route;
use App\Models\CattleType;
use App\Models\Supplier;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function index(Request $request) {
        $query = Logbook::with(['route', 'cattleType', 'supplier'])->latest();

        if ($request->has('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $logbooks = $query->get();
        return view('dashboard.logbooks.index', compact('logbooks'));
    }

    public function create() {
        $routes = Route::all();
        return view('dashboard.logbooks.create', compact('routes'));
    }

    public function store(Request $request) {
        $request->validate(['driver_name' => 'required', 'license_plate' => 'required', 'route_id' => 'required', 'pic_name' => 'required']);
        Logbook::create(array_merge($request->all(), ['status' => 'Muat']));
        return redirect()->route('logbooks.index')->with('success', 'Catatan muat ditambahkan.');
    }

    public function edit(Logbook $logbook) {
        $cattleTypes = CattleType::all();
        $suppliers = Supplier::all();
        return view('dashboard.logbooks.edit', compact('logbook', 'cattleTypes', 'suppliers'));
    }

    public function update(Request $request, Logbook $logbook) {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'cattle_type_id' => 'required|exists:cattle_types,id',
            'headcount' => 'required|integer|min:1',
            'gross_weight' => 'required|numeric|min:0',
            'tare_weight' => 'required|numeric|min:0',
            'additional_costs' => 'nullable|numeric|min:0',
            'additional_costs_notes' => 'nullable|string'
        ]);
        
        $net_weight = $request->gross_weight - $request->tare_weight;
        $logbook->update([
            'supplier_id' => $request->supplier_id,
            'cattle_type_id' => $request->cattle_type_id,
            'headcount' => $request->headcount,
            'gross_weight' => $request->gross_weight,
            'tare_weight' => $request->tare_weight,
            'net_weight' => $net_weight,
            'additional_costs' => $request->additional_costs ?? 0,
            'additional_costs_notes' => $request->additional_costs_notes,
            'status' => 'Selesai'
        ]);
        return redirect()->route('logbooks.index')->with('success', 'Penimbangan selesai.');
    }

    public function print(Logbook $logbook) {
        return view('dashboard.logbooks.print', compact('logbook'));
    }

    public function destroy(Logbook $logbook) {
        $logbook->delete();
        return redirect()->route('logbooks.index')->with('success', 'Data logbook berhasil dihapus sementara (arsip).');
    }

    public function restore($id) {
        $logbook = Logbook::withTrashed()->findOrFail($id);
        $logbook->restore();
        return redirect()->route('logbooks.index')->with('success', 'Data logbook berhasil dikembalikan dari arsip.');
    }

    public function export(Request $request) {
        $query = Logbook::with(['route', 'cattleType', 'supplier'])->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $logbooks = $query->get();
        return response()->json($logbooks);
    }
}

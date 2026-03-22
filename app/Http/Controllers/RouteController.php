<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index() {
        $routes = Route::all();
        return view('dashboard.routes.index', compact('routes'));
    }

    public function create() {
        return view('dashboard.routes.create');
    }

    public function store(Request $request) {
        $request->validate(['origin' => 'required', 'destination' => 'required', 'driver_money' => 'required|numeric']);
        Route::create($request->all());
        return redirect()->route('routes.index')->with('success', 'Rute ditambahkan.');
    }

    public function edit(Route $route) {
        return view('dashboard.routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route) {
        $request->validate(['origin' => 'required', 'destination' => 'required', 'driver_money' => 'required|numeric']);
        $route->update($request->all());
        return redirect()->route('routes.index')->with('success', 'Rute diperbarui.');
    }

    public function destroy(Route $route) {
        // Cek jika rute sudah dipakai di logbook
        if (\App\Models\Logbook::where('route_id', $route->id)->exists()) {
            return redirect()->route('routes.index')->with('error', 'Rute ini tidak bisa dihapus karena sudah dipakai dalam Logbook.');
        }
        $route->delete();
        return redirect()->route('routes.index')->with('success', 'Rute dihapus.');
    }
}

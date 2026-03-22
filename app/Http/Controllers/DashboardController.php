<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Route;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalTonase = Logbook::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('net_weight');
        
        $allCompletedThisMonth = Logbook::with('route')->where('status', 'Selesai')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->get();

        $totalUangJalan = $allCompletedThisMonth->sum(function($log) {
            return ($log->route ? $log->route->driver_money : 0) + $log->additional_costs;
        });

        $trucksToday = Logbook::whereDate('created_at', now()->format('Y-m-d'))->count();

        $chartData = Logbook::selectRaw('DATE(created_at) as date, SUM(net_weight) as total_weight')
            ->where('status', 'Selesai')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
            
        $dates = $chartData->pluck('date');
        $weights = $chartData->pluck('total_weight');

        $stats = [
            'total_tonase' => $totalTonase,
            'total_uang_jalan' => $totalUangJalan,
            'trucks_today' => $trucksToday,
            'total_logbooks' => Logbook::count(),
            'completed' => Logbook::where('status', 'Selesai')->count(),
            'unweighed' => Logbook::where('status', 'Muat')->count()
        ];

        return view('dashboard.index', compact('stats', 'dates', 'weights'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Route;

class DashboardController extends Controller
{
    public function index()
    {
        $thirtyDaysAgo = now()->subDays(30);

        // Tonase 30 hari terakhir
        $totalTonase = Logbook::where('status', 'Selesai')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('net_weight');

        // Headcount sapi 30 hari terakhir
        $totalHeadcount = Logbook::where('status', 'Selesai')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('headcount');

        // Uang jalan 30 hari terakhir
        $allCompleted30 = Logbook::with('route')
            ->where('status', 'Selesai')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->get();

        $totalUangJalan = $allCompleted30->sum(function ($log) {
            return ($log->route ? $log->route->driver_money : 0) + $log->additional_costs;
        });

        // Ritase truk hari ini
        $trucksToday = Logbook::whereDate('created_at', now()->format('Y-m-d'))->count();

        // Ritase truk 30 hari terakhir
        $trucks30Days = Logbook::where('created_at', '>=', $thirtyDaysAgo)->count();

        // Chart data 30 hari terakhir
        $chartData = Logbook::selectRaw('DATE(created_at) as date, SUM(net_weight) as total_weight')
            ->where('status', 'Selesai')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $chartData->pluck('date');
        $weights = $chartData->pluck('total_weight');

        $stats = [
            'total_tonase'    => $totalTonase,
            'total_headcount' => $totalHeadcount,
            'total_uang_jalan'=> $totalUangJalan,
            'trucks_today'    => $trucksToday,
            'trucks_30_days'  => $trucks30Days,
            'total_logbooks'  => Logbook::count(),
            'completed'       => Logbook::where('status', 'Selesai')->count(),
            'unweighed'       => Logbook::where('status', 'Muat')->count(),
        ];

        return view('dashboard.index', compact('stats', 'dates', 'weights'));
    }
}

@extends('layouts.app')
@section('title', 'Analytics Kinerja Logistik')
@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
    <div>
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Dashboard Overview</h2>
        <p class="text-sm text-gray-500 mt-1">Performa bulan {{ now()->translatedFormat('F Y') }} & tren riwayat terkini.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 font-bold rounded-full text-sm border border-green-200 shadow-sm">
            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Sistem Live
        </span>
    </div>
</div>

<!-- Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-4">
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-blue-100 text-xs text-opacity-80 uppercase tracking-widest font-bold">Tonase Bulan Ini</h3>
            <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
        </div>
        <p class="text-3xl font-extrabold">{{ number_format($stats['total_tonase'], 0, ',', '.') }}<span class="text-base font-medium text-blue-200 ml-1">KG</span></p>
    </div>
    
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-emerald-100 text-xs text-opacity-80 uppercase tracking-widest font-bold">Uang Jalan / Sangu</h3>
            <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-2xl font-extrabold">Rp {{ number_format($stats['total_uang_jalan'], 0, ',', '.') }}</p>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-orange-100 text-xs text-opacity-80 uppercase tracking-widest font-bold">Ritase Truk Hari Ini</h3>
            <svg class="w-6 h-6 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
        </div>
        <p class="text-3xl font-extrabold">{{ $stats['trucks_today'] }}<span class="text-base font-medium text-orange-200 ml-1">Truk</span></p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 flex flex-col justify-center transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between text-sm mb-3">
            <span class="text-gray-500 font-semibold">Total Selesai</span>
            <span class="text-green-600 font-black">{{ $stats['completed'] }} Trip</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500 font-semibold">Berstatus Muat</span>
            <span class="text-yellow-600 font-black">{{ $stats['unweighed'] }} Trip</span>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex justify-between text-sm items-center">
                <span class="text-gray-400 font-bold uppercase tracking-wider text-xs">Total Riwayat</span>
                <span class="bg-gray-800 text-white rounded-full px-2 py-0.5 text-xs font-bold">{{ $stats['total_logbooks'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Chart Area -->
<div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-800">Tren Tonase (14 Hari Terakhir)</h3>
        <select class="text-xs border-gray-300 rounded-lg text-gray-600 font-medium focus:ring-blue-500 focus:border-blue-500">
            <option>14 Hari Terakhir</option>
        </select>
    </div>
    <div class="relative h-72 w-full">
        <canvas id="tonnageChart"></canvas>
    </div>
</div>

<!-- Scripts for Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('tonnageChart').getContext('2d');
        
        // Gradient for line chart area
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)'); // blue-600
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

        const labels = {!! json_encode($dates) !!};
        const dataPoints = {!! json_encode($weights) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                }),
                datasets: [{
                    label: 'Total Net Weight (KG)',
                    data: dataPoints,
                    borderColor: '#2563eb', // blue-600
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937', // gray-800
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('id-ID') + ' KG';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Inter', sans-serif", size: 11 }, color: '#6b7280' }
                    },
                    y: {
                        grid: { color: '#f3f4f6', drawBorder: false, borderDash: [5, 5] },
                        ticks: { 
                            font: { family: "'Inter', sans-serif", size: 11 }, 
                            color: '#6b7280',
                            callback: function(value) {
                                return value >= 1000 ? (value / 1000) + 'T' : value;
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection

@extends('layouts.app')
@section('title', 'Analytics Kinerja Logistik')
@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
    <div>
        <h2 class="text-3xl font-black text-gray-800 tracking-tight">Dashboard Overview</h2>
        <p class="text-sm text-gray-500 mt-1">Performa <span class="font-bold text-blue-600">30 hari terakhir</span> &amp; tren operasional terkini.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 font-bold rounded-full text-sm border border-blue-200 shadow-sm">
            📅 30 Hari Terakhir
        </span>
        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 font-bold rounded-full text-sm border border-green-200 shadow-sm">
            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Sistem Live
        </span>
    </div>
</div>

<!-- Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-4">
    <!-- Tonase 30 Hari -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-blue-100 text-xs uppercase tracking-widest font-bold">Tonase 30 Hari</h3>
            <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
        </div>
        <p class="text-3xl font-extrabold">{{ number_format($stats['total_tonase'], 0, ',', '.') }}<span class="text-base font-medium text-blue-200 ml-1">KG</span></p>
    </div>

    <!-- Headcount Sapi 30 Hari -->
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-indigo-100 text-xs uppercase tracking-widest font-bold">Total Sapi 30 Hari</h3>
            <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <p class="text-3xl font-extrabold">{{ number_format($stats['total_headcount'], 0, ',', '.') }}<span class="text-base font-medium text-indigo-200 ml-1">Ekor</span></p>
    </div>

    <!-- Uang Jalan 30 Hari -->
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl shadow-lg p-6 text-white transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-emerald-100 text-xs uppercase tracking-widest font-bold">Uang Jalan 30 Hari</h3>
            <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-2xl font-extrabold">Rp {{ number_format($stats['total_uang_jalan'], 0, ',', '.') }}</p>
    </div>

    <!-- Status & Ritase -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 flex flex-col justify-center transform hover:-translate-y-1 transition duration-300">
        <div class="flex justify-between text-sm mb-3">
            <span class="text-gray-500 font-semibold">Truk Hari Ini</span>
            <span class="text-orange-600 font-black">{{ $stats['trucks_today'] }} Trip</span>
        </div>
        <div class="flex justify-between text-sm mb-3">
            <span class="text-gray-500 font-semibold">Ritase 30 Hari</span>
            <span class="text-blue-600 font-black">{{ $stats['trucks_30_days'] }} Trip</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500 font-semibold">Menunggu Timbang</span>
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
        <div>
            <h3 class="text-lg font-bold text-gray-800">Tren Tonase (30 Hari Terakhir)</h3>
            <p class="text-xs text-gray-400 mt-0.5">Berat bersih sapi (KG) per hari</p>
        </div>
        <span class="text-xs bg-blue-50 border border-blue-200 text-blue-700 font-bold px-3 py-1 rounded-full">📅 30 Hari</span>
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
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
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
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
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

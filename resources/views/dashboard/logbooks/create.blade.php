@extends('layouts.app')
@section('title', 'Pencatatan Headcount & Pemuatan (Fase 1)')
@section('content')
<div class="bg-white shadow-xl rounded-2xl p-8 max-w-3xl mx-auto border border-gray-100">
    <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
        <div class="bg-blue-100 text-blue-600 p-3 rounded-xl mr-4">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
        </div>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Form Checklist Pemuatan Sapi</h2>
    </div>

    <form action="{{ route('logbooks.store') }}" method="POST">
        @csrf
        
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">I. Driver & Ekspedisi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-xl border border-gray-200">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Driver</label>
                <input type="text" name="driver_name" required placeholder="Contoh: Budi Santoso" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Polisi Kendaraan</label>
                <input type="text" name="license_plate" required placeholder="Contoh: N 8820 P" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 font-bold uppercase leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Penanggung Jawab (PIC)</label>
                <input type="text" name="pic_name" required placeholder="Contoh: Pak Agus" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
        </div>
        
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">II. Penugasan Rute</h3>
        <div class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
            <label class="block text-blue-900 text-sm font-bold mb-2">Pilih Destinasi Rute Pengiriman</label>
            <select name="route_id" required class="shadow border-transparent rounded-lg w-full py-3.5 px-4 text-gray-800 font-semibold leading-tight focus:outline-none focus:ring-4 focus:ring-blue-300 bg-white transition cursor-pointer">
                <option value="">-- Tekan untuk Memilih Rute --</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}">{{ mb_strtoupper($route->origin) }} ➔ {{ mb_strtoupper($route->destination) }} (Uang Jalan: Rp {{ number_format($route->driver_money, 0, ',', '.') }})</option>
                @endforeach
            </select>
            @if($routes->isEmpty())
                <div class="mt-3 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                  <span class="block sm:inline">Database Rute Kosong. Anda harus <a href="{{ route('routes.create') }}" class="underline font-bold">membuat rute</a> terlebih dahulu.</span>
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end space-x-4 pt-6 mt-4 border-t border-gray-100">
            <a href="{{ route('logbooks.index') }}" class="inline-block align-baseline font-bold text-gray-500 hover:text-gray-800 transition">
                Batalkan Draft
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-indigo-700 text-white font-black tracking-wide py-4 px-10 rounded-xl shadow-xl hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all transform hover:-translate-y-1">
                SELESAI PEMUATAN & LANJUT TIMBANG →
            </button>
        </div>
    </form>
</div>
@endsection

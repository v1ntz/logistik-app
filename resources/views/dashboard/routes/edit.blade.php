@extends('layouts.app')
@section('title', 'Edit Data Rute')
@section('content')
<div class="bg-white shadow-xl rounded-2xl p-8 max-w-2xl mx-auto border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Rute Operasional</h2>
    <form action="{{ route('routes.update', $route) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Asal (Origin)</label>
            <input type="text" name="origin" value="{{ $route->origin }}" required class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tujuan (Destination)</label>
            <input type="text" name="destination" value="{{ $route->destination }}" required class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        <div class="mb-8">
            <label class="block text-gray-700 text-sm font-bold mb-2">Estimasi Uang Jalan Driver (Rp)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 font-semibold">Rp</span>
                </div>
                <input type="number" name="driver_money" value="{{ round($route->driver_money) }}" required class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 pl-10 pr-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
        </div>

        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
            <a href="{{ route('routes.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900 transition">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300 transition transform hover:-translate-y-0.5">
                Update Data Rute
            </button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Tambah Rute Baru')
@section('content')
<div class="bg-white shadow-xl rounded-2xl p-8 max-w-2xl mx-auto border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Detail Rute Operasional</h2>
    <form action="{{ route('routes.store') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Asal (Origin)</label>
            <input type="text" name="origin" required placeholder="Contoh: Peternakan Sapi Banyuwangi" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tujuan (Destination)</label>
            <input type="text" name="destination" required placeholder="Contoh: Pelabuhan Tanjung Perak" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        <div class="mb-8">
            <label class="block text-gray-700 text-sm font-bold mb-2">Estimasi Uang Jalan Driver (Rp)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 font-semibold">Rp</span>
                </div>
                <input type="number" name="driver_money" required placeholder="2500000" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 pl-10 pr-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <p class="text-sm text-gray-500 mt-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                Uang ini akan otomatis ditarik saat Surat Jalan dicetak berdasarkan pilihan rute ini.
            </p>
        </div>

        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
            <a href="{{ route('routes.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-900 transition">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300 transition transform hover:-translate-y-0.5">
                Simpan Data Rute
            </button>
        </div>
    </form>
</div>
@endsection

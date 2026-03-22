@extends('layouts.app')
@section('title', 'Master Data Importir')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Master Data Importir</h1>
        <p class="text-gray-600 text-sm mt-1">Kelola daftar pihak importir asal muat sapi.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tambah Importir</h2>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Importir</label>
                    <input type="text" name="name" required placeholder="PT. / CV. / Importir" class="shadow-sm appearance-none border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 w-full py-2 px-3 text-gray-700 mb-3">
                    
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi (Opsional)</label>
                    <input type="text" name="location" placeholder="Kota / Kabupaten" class="shadow-sm appearance-none border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 w-full py-2 px-3 text-gray-700">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">Simpan Importir</button>
            </form>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Importir</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ strtoupper($supplier->name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $supplier->location ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-left">
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus data importir?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($suppliers->isEmpty())
                    <tr><td colspan="3" class="px-6 py-6 text-center text-sm text-gray-500">Belum ada data master importir.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

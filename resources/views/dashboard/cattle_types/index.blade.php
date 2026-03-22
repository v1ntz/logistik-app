@extends('layouts.app')
@section('title', 'Master Jenis Sapi')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Master Data Jenis Sapi</h1>
        <p class="text-gray-600 text-sm mt-1">Kelola daftar tipe sapi yang tersedia untuk input petugas timbang.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tambah Jenis Sapi</h2>
            <form action="{{ route('cattle-types.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Jenis Sapi</label>
                    <input type="text" name="name" required placeholder="Contoh: Limousin" class="shadow-sm appearance-none border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 w-full py-2 px-3 text-gray-700">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">Simpan Master Data</button>
            </form>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Sapi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($cattleTypes as $type)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $type->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ strtoupper($type->name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-left">
                            <form action="{{ route('cattle-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Hapus data jenis sapi ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($cattleTypes->isEmpty())
                    <tr><td colspan="3" class="px-6 py-6 text-center text-sm text-gray-500">Belum ada data master jenis sapi.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

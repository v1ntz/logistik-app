@extends('layouts.app')
@section('title', 'Data Rute')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
        Manajemen Rute & Uang Jalan
    </h2>
    <a href="{{ route('routes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform hover:-translate-y-0.5">
        + Tambah Rute
    </a>
</div>

<div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Origin</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Destination</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Uang Jalan (Driver)</th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($routes as $route)
            <tr class="hover:bg-blue-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">#{{ $route->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $route->origin }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $route->destination }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Rp {{ number_format($route->driver_money, 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                    <a href="{{ route('routes.edit', $route) }}" class="text-blue-600 hover:text-blue-900 mr-3 font-bold">Edit</a>
                    <form action="{{ route('routes.destroy', $route) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rute ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                    Belum ada data rute. <a href="{{ route('routes.create') }}" class="text-blue-600 hover:underline">Tambah sekarang</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

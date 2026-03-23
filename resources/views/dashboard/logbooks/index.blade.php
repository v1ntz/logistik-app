@extends('layouts.app')
@section('title', 'Logbook Bongkar Muat')
@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Data Logbook Operasional
    </h2>
    <div class="flex space-x-3 w-full sm:w-auto">
        <button type="button" onclick="document.getElementById('exportModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 shadow transition transform hover:-translate-y-0.5 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Rekap Excel
        </button>
        @if(request()->has('trashed'))
        <a href="{{ route('logbooks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 shadow transition transform hover:-translate-y-0.5 rounded-lg flex items-center justify-center">
            Kembali ke Data Aktif
        </a>
        @else
        <a href="{{ route('logbooks.index', ['trashed' => 1]) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 shadow transition transform hover:-translate-y-0.5 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Arsip Dihapus ({{ \App\Models\Logbook::onlyTrashed()->count() }})
        </a>
        @endif
        <a href="{{ route('logbooks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 shadow transition transform hover:-translate-y-0.5 rounded-lg flex items-center justify-center">
            + Catat Pemuatan Baru
        </a>
    </div>
</div>

<div class="mb-6 bg-white p-4 rounded-xl shadow border border-gray-100 flex items-center justify-between flex-wrap gap-4">
    <form action="{{ route('logbooks.index') }}" method="GET" class="flex items-end space-x-4 w-full sm:w-auto">
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
        </div>
        <button type="submit" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg font-bold text-sm shadow transition">
            Terapkan Filter
        </button>
        @if(request()->has('start_date'))
        <a href="{{ route('logbooks.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 underline ml-2">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white shadow-lg rounded-xl overflow-x-auto border border-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Waktu / Kendaraan</th>
                <th class="px-6 py-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tujuan</th>
                <th class="px-6 py-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Muatan Sapi</th>
                <th class="px-6 py-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tonase (Net)</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase">Status</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($logbooks as $log)
            <tr class="hover:bg-blue-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $log->created_at->format('d/m/Y, H:i') }}</div>
                    <div class="text-sm font-bold text-gray-900">{{ $log->driver_name }} <span class="text-xs text-gray-500 font-normal ml-1">({{ $log->pic_name }})</span></div>
                    <div class="text-sm font-semibold text-gray-500 uppercase">{{ $log->license_plate }}</div>
                    <div class="text-[10px] font-bold text-orange-700 bg-orange-100 inline-block px-1.5 py-0.5 rounded border border-orange-200 mt-1 uppercase">{{ optional($log->supplier)->name ?? 'NO IMPORTIR' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs font-medium text-blue-600 mt-1 inline-flex items-center px-1.5 py-0.5 rounded border border-blue-200 bg-blue-50">
                        {{ optional($log->route)->origin }} ➔ {{ optional($log->route)->destination }}
                    </div>
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    <div class="text-sm font-black text-indigo-700 bg-indigo-50 border border-indigo-100 rounded px-2 py-1 inline-block">
                        {{ $log->headcount ?? '-' }} <span class="text-xs font-bold text-indigo-400">Ekor</span>
                    </div>
                    @if($log->cattleType)
                        <div class="text-[10px] uppercase font-bold text-indigo-500 mt-1 tracking-wider">{{ $log->cattleType->name }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $log->net_weight ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $log->net_weight ? number_format($log->net_weight, 2) . ' KG' : 'Belum Timbang' }}
                    @if($log->additional_costs > 0)
                        <div class="text-[10px] font-black text-red-500 mt-1 bg-red-50 inline-block px-1.5 py-0.5 rounded border border-red-100">
                            + UANG SUSUL
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($log->status == 'Muat')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 shadow-sm border border-yellow-200 uppercase tracking-wide">Menunggu Timbang</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 shadow-sm border border-green-200 uppercase tracking-wide">Selesai Berkas</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    @if($log->trashed())
                        <form action="{{ route('logbooks.restore', $log->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center text-emerald-600 font-bold hover:text-white bg-emerald-50 ring-1 ring-emerald-300 hover:bg-emerald-600 px-4 py-1.5 rounded-lg transition duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Restore Data
                            </button>
                        </form>
                    @elseif($log->status == 'Muat')
                        <a href="{{ route('logbooks.edit', $log) }}" class="inline-flex items-center text-blue-600 font-bold hover:text-white bg-blue-50 ring-1 ring-blue-300 hover:bg-blue-600 px-4 py-1.5 rounded-lg transition duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            Input Timbang
                        </a>
                        <form action="{{ route('logbooks.destroy', $log) }}" method="POST" class="inline-block swal-confirm-delete" data-message="Hapus logbook keberangkatan ini? Data tersimpan aman di arsip.">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="this.closest('form').dispatchEvent(new Event('submit', { cancelable: true }))" title="Hapus Data" class="inline-flex items-center text-red-600 font-bold hover:text-white bg-red-50 ring-1 ring-red-300 hover:bg-red-600 px-2 py-1.5 rounded-lg transition duration-200 shadow-sm ml-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('logbooks.print', $log) }}" target="_blank" class="inline-flex items-center text-purple-600 font-bold hover:text-white bg-purple-50 ring-1 ring-purple-300 hover:bg-purple-600 px-4 py-1.5 rounded-lg transition duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak
                        </a>
                        <form action="{{ route('logbooks.destroy', $log) }}" method="POST" class="inline-block swal-confirm-delete" data-message="Hapus logbook yang sudah selesai ini? Akses cetaknya akan hilang.">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="this.closest('form').dispatchEvent(new Event('submit', { cancelable: true }))" title="Hapus Data" class="inline-flex items-center text-red-600 font-bold hover:text-white bg-red-50 ring-1 ring-red-300 hover:bg-red-600 px-2 py-1.5 rounded-lg transition duration-200 shadow-sm ml-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="text-gray-400 mb-2">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <span class="block text-gray-500 text-lg font-medium">Belum ada data muat.</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Export Modal -->
<div id="exportModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('exportModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
            <form action="{{ route('logbooks.export') }}" method="GET">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 border-b pb-2" id="modal-title">Parameter Kop Surat Excel</h3>
                            <p class="text-xs text-gray-500 mt-1 mb-4">Kosongkan jika tidak ingin dicetak di Excel.</p>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase">Nama Kapal</label>
                                    <input type="text" name="nama_kapal" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: MV. BALHA ONE">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase">ETA</label>
                                    <input type="text" name="eta" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: 22-Mar-26">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase">Kade</label>
                                    <input type="text" name="kade" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: 114">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase">Consignee</label>
                                    <input type="text" name="consignee" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: PT. CINTA ASIH FARM">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase">Party</label>
                                    <input type="text" name="party" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: 60 EKOR">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase">Tipe Sapi</label>
                                    <input type="text" name="tipe_sapi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: MEDIUM HEIFERS">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase mt-2">Lokasi/Tgl TTD</label>
                                    <input type="text" name="lokasi_ttd" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Tanjung Priok, 22 Maret 2026">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 uppercase mt-2">Nama TTD</label>
                                    <input type="text" name="nama_ttd" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: LIAN">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="submit" onclick="document.getElementById('exportModal').classList.add('hidden')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-bold text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Download Excel
                    </button>
                    <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

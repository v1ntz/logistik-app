@extends('layouts.app')
@section('title', 'Master Data Kapal')
@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">🚢 Master Data Kapal</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola data kapal dan manifest importir per kapal.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Kapal -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tambah Kapal Baru</h2>
            <form action="{{ route('kapals.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kapal <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kapal" required placeholder="Contoh: MV. BALHA ONE"
                        class="shadow-sm appearance-none border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full py-2.5 px-3 text-gray-700 uppercase">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">ETA (Estimasi Tiba)</label>
                    <input type="text" name="eta" placeholder="Contoh: 22-Mar-26"
                        class="shadow-sm appearance-none border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full py-2.5 px-3 text-gray-700">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow transition">
                    + Simpan Kapal
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar Kapal & Manifest -->
    <div class="lg:col-span-2 space-y-4">
        @forelse($kapals as $kapal)
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
            <!-- Header Kapal -->
            <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="text-white font-black text-lg uppercase tracking-wide">🚢 {{ $kapal->nama_kapal }}</h3>
                    @if($kapal->eta)
                    <p class="text-slate-300 text-sm mt-0.5">ETA: {{ $kapal->eta }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                        {{ $kapal->manifests->count() }} Importir
                    </span>
                    <form action="{{ route('kapals.destroy', $kapal) }}" method="POST"
                        onsubmit="return confirm('Hapus kapal {{ $kapal->nama_kapal }}? Semua manifest akan terhapus.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-300 hover:text-red-100 text-xs font-bold bg-red-900/30 hover:bg-red-900/50 px-3 py-1 rounded transition">
                            Hapus Kapal
                        </button>
                    </form>
                </div>
            </div>

            <!-- Manifest List -->
            <div class="p-4">
                @if($kapal->manifests->isEmpty())
                <p class="text-center text-gray-400 text-sm py-4">Belum ada manifest. Tambahkan importir di bawah.</p>
                @else
                <div class="space-y-2 mb-4">
                    @foreach($kapal->manifests as $manifest)
                    <div class="flex items-center justify-between bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="font-black text-indigo-800 text-sm uppercase">Importir: {{ optional($manifest->importir)->name ?? '-' }}</span>
                                @if($manifest->exporter)
                                <span class="text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded">🚢 Eksportir: {{ optional($manifest->exporter)->name }}</span>
                                @endif
                                @if($manifest->consignee)
                                <span class="text-xs text-gray-600 bg-white border border-gray-200 px-2 py-0.5 rounded">📋 {{ $manifest->consignee }}</span>
                                @endif
                                @if($manifest->kade)
                                <span class="text-xs text-gray-600 bg-white border border-gray-200 px-2 py-0.5 rounded">⚓ Kade {{ $manifest->kade }}</span>
                                @endif
                                @if($manifest->party)
                                <span class="text-xs font-bold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded">
                                    {{ $manifest->totalMuat() }}/{{ $manifest->party }} Ekor
                                </span>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('kapals.manifests.destroy', $manifest) }}" method="POST"
                            onsubmit="return confirm('Hapus manifest importir ini?')" class="ml-3">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition">
                                ✕
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Form Tambah Manifest -->
                <details class="group">
                    <summary class="cursor-pointer text-sm font-bold text-blue-600 hover:text-blue-800 list-none flex items-center gap-1">
                        <span class="group-open:hidden">+ Tambah Manifest Importir ke Kapal Ini</span>
                        <span class="hidden group-open:inline">▲ Tutup Form</span>
                    </summary>
                    <form action="{{ route('kapals.manifests.store', $kapal) }}" method="POST" class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Importir (Indonesia) <span class="text-red-500">*</span></label>
                                <select name="importir_id" required class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">-- Pilih Importir Indonesia --</option>
                                    @foreach($importirs as $imp)
                                    <option value="{{ $imp->id }}">{{ strtoupper($imp->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Eksportir (Australia)</label>
                                <select name="exporter_id" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">-- Pilih Eksportir Australia (Opsional) --</option>
                                    @foreach($exporters as $exp)
                                    <option value="{{ $exp->id }}">{{ strtoupper($exp->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Consignee</label>
                                <input type="text" name="consignee" placeholder="Contoh: PT. CINTA ASIH FARM"
                                    class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kade</label>
                                <input type="text" name="kade" placeholder="Contoh: 114"
                                    class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Party (Total Ekor)</label>
                                <input type="number" name="party" min="1" placeholder="Contoh: 60"
                                    class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-5 rounded-lg transition">
                            Simpan Manifest
                        </button>
                    </form>
                </details>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow border border-gray-100 p-12 text-center">
            <p class="text-gray-400 text-lg">🚢 Belum ada data kapal.</p>
            <p class="text-gray-400 text-sm mt-1">Tambahkan kapal menggunakan form di sebelah kiri.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

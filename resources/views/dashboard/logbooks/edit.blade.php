@extends('layouts.app')
@section('title', 'Input Jembatan Timbang (Fase 2)')
@section('content')
<div class="bg-white shadow-2xl rounded-3xl p-8 max-w-3xl mx-auto border border-gray-200">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 pb-6 border-b-2 border-gray-100 space-y-4 md:space-y-0">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Loket Jembatan Timbang</h2>
        <span class="bg-blue-100 text-blue-800 text-sm font-black px-4 py-2 rounded-full uppercase tracking-wider flex items-center shadow-sm">
            Truk ID: #{{ str_pad($logbook->id, 4, '0', STR_PAD_LEFT) }}
        </span>
    </div>
    
    <!-- Informasi Trip -->
    <div class="mb-10 bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200 shadow-inner">
        <div class="space-y-4">
            <div>
                <p class="text-xs text-blue-600 uppercase font-black tracking-widest">Driver / Nopol Truk</p>
                <p class="font-extrabold text-gray-900 text-xl">{{ $logbook->driver_name }} <span class="text-gray-500 text-lg uppercase bg-white px-2 py-0.5 rounded border border-gray-300 shadow-sm ml-2">{{ $logbook->license_plate }}</span></p>
            </div>
            <div>
                <p class="text-xs text-blue-600 uppercase font-black tracking-widest">Rute Ekspedisi</p>
                <p class="font-bold text-gray-800 bg-white inline-block px-3 py-1 rounded shadow-sm border border-gray-200 mt-1">
                    {{ optional($logbook->route)->origin }} &rarr; {{ optional($logbook->route)->destination }}
                </p>
            </div>
            <div class="pt-2 border-t border-gray-200">
                <p class="text-xs text-gray-500 uppercase font-bold tracking-widest">PIC / Penanggung Jawab</p>
                <p class="font-bold text-gray-800">{{ $logbook->pic_name }}</p>
            </div>
        </div>
    </div>

    <!-- Form Timbangan -->
    <form action="{{ route('logbooks.update', $logbook) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative mb-8">
            <div class="space-y-3">
                <label class="flex justify-between items-center text-gray-900 text-sm font-black tracking-wide uppercase">
                    <span>1. Asal Peternak (Supplier)</span>
                    <span class="text-xs font-semibold text-gray-500 normal-case bg-gray-100 px-2 py-0.5 rounded">(Supplier)</span>
                </label>
                <div class="relative">
                    <select name="supplier_id" required class="shadow-inner appearance-none border-2 border-gray-300 rounded-xl w-full py-4 px-6 text-xl font-bold text-gray-800 focus:outline-none focus:ring-0 focus:border-purple-500 transition">
                        <option value="" disabled {{ !$logbook->supplier_id ? 'selected' : '' }}>Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $logbook->supplier_id == $supplier->id ? 'selected' : '' }}>{{ strtoupper($supplier->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-3">
                <label class="flex justify-between items-center text-gray-900 text-sm font-black tracking-wide uppercase">
                    <span>2. Jenis Sapi</span>
                    <span class="text-xs font-semibold text-gray-500 normal-case bg-gray-100 px-2 py-0.5 rounded">(Kategori Sapi)</span>
                </label>
                <div class="relative">
                    <select name="cattle_type_id" required class="shadow-inner appearance-none border-2 border-gray-300 rounded-xl w-full py-4 px-6 text-xl font-bold text-gray-800 focus:outline-none focus:ring-0 focus:border-purple-500 transition">
                        <option value="" disabled {{ !$logbook->cattle_type_id ? 'selected' : '' }}>Pilih Jenis Sapi</option>
                        @foreach($cattleTypes as $type)
                            <option value="{{ $type->id }}" {{ $logbook->cattle_type_id == $type->id ? 'selected' : '' }}>{{ strtoupper($type->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative mb-8">
            <div class="space-y-3">
                <label class="flex justify-between items-center text-gray-900 text-sm font-black tracking-wide uppercase">
                    <span>3. Headcount</span>
                    <span class="text-xs font-semibold text-gray-500 normal-case bg-gray-100 px-2 py-0.5 rounded">(Fisik)</span>
                </label>
                <div class="relative">
                    <input type="number" name="headcount" required min="1" placeholder="0" value="{{ $logbook->headcount }}" class="shadow-inner appearance-none border-2 border-gray-300 rounded-xl w-full py-4 px-6 text-2xl font-black text-gray-800 focus:outline-none focus:ring-0 focus:border-purple-500 transition pr-16 text-right">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-6 text-gray-400 font-bold">- SAPI</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative">

            <div class="space-y-3">
                <label class="flex justify-between items-center text-gray-900 text-sm font-black tracking-wide uppercase">
                    <span>3. Gross Weight</span>
                    <span class="text-xs font-semibold text-gray-500 normal-case bg-gray-100 px-2 py-0.5 rounded">(Isi)</span>
                </label>
                <div class="relative">
                    <input type="number" step="0.01" name="gross_weight" id="gross" required placeholder="0" class="shadow-inner appearance-none border-2 border-gray-300 rounded-xl w-full py-4 px-6 text-2xl font-black text-gray-800 focus:outline-none focus:ring-0 focus:border-purple-500 transition pr-16 text-right">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-6 text-gray-400 font-bold">KG</span>
                </div>
            </div>
            
            <div class="space-y-3">
                <label class="flex justify-between items-center text-gray-900 text-sm font-black tracking-wide uppercase">
                    <span>4. Tare Weight</span>
                    <span class="text-xs font-semibold text-gray-500 normal-case bg-gray-100 px-2 py-0.5 rounded">(Kosong)</span>
                </label>
                <div class="relative">
                    <input type="number" step="0.01" name="tare_weight" id="tare" required placeholder="0" class="shadow-inner appearance-none border-2 border-gray-300 rounded-xl w-full py-4 px-6 text-2xl font-black text-gray-800 focus:outline-none focus:ring-0 focus:border-purple-500 transition pr-16 text-right">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-6 text-gray-400 font-bold">KG</span>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-6 mb-8 relative">
            <h3 class="text-lg font-black text-yellow-800 mb-4 uppercase tracking-wide">Biaya Ekstra & Uang Susul (Opsional)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="flex justify-between items-center text-yellow-900 text-sm font-bold">
                        <span>Tambahan Uang Jalan (Rp)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-bold text-gray-500">Rp</span>
                        <input type="number" name="additional_costs" min="0" value="{{ $logbook->additional_costs != 0 ? intval($logbook->additional_costs) : '' }}" placeholder="0" class="shadow-inner appearance-none border-2 border-yellow-300 rounded-xl w-full py-3 pl-12 pr-4 text-xl font-bold text-gray-800 focus:outline-none focus:ring-0 focus:border-yellow-500 transition">
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="flex justify-between items-center text-yellow-900 text-sm font-bold">
                        <span>Keterangan Biaya Tambahan</span>
                    </label>
                    <input type="text" name="additional_costs_notes" placeholder="Contoh: Tambah e-toll, kuli bongkar..." value="{{ $logbook->additional_costs_notes }}" class="shadow-inner appearance-none border-2 border-yellow-300 rounded-xl w-full py-3 px-4 text-md font-bold text-gray-800 focus:outline-none focus:ring-0 focus:border-yellow-500 transition">
                </div>
            </div>
        </div>

        <div class="p-8 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border-2 border-green-200 shadow-sm relative overflow-hidden mt-10">
            <div class="absolute inset-y-0 left-0 w-2 bg-green-500 rounded-l-2xl"></div>
            <label class="block text-green-900 text-sm font-black tracking-widest uppercase mb-1 text-center">Netto / Berat Sapi Murni (Live Weight)</label>
            <div class="flex items-end justify-center">
                <div id="net-display" class="text-center text-6xl font-black text-green-600 tracking-tight">0.00</div>
                <div class="text-green-600 font-bold text-2xl ml-2 mb-1">KG</div>
            </div>
        </div>

        <div class="flex flex-col-reverse md:flex-row items-center justify-between pt-6 border-t font-sans border-gray-200 gap-4 md:gap-0">
            <a href="{{ route('logbooks.index') }}" class="w-full md:w-auto text-center align-baseline font-bold text-gray-500 hover:text-gray-900 transition">
                ← Kembali
            </a>
            <button type="submit" class="w-full md:w-auto bg-purple-600 hover:bg-purple-700 text-white font-black uppercase tracking-wider py-4 px-10 rounded-xl shadow-xl hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-purple-300 transition-all transform hover:-translate-y-1">
                Sahkan Timbangan
            </button>
        </div>
    </form>
</div>

<script>
    const grossInput = document.getElementById('gross');
    const tareInput = document.getElementById('tare');
    const netDisplay = document.getElementById('net-display');

    function calculateNet() {
        const gross = parseFloat(grossInput.value) || 0;
        const tare = parseFloat(tareInput.value) || 0;
        const net = gross - tare;
        
        if (net > 0) {
            netDisplay.innerText = net.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            netDisplay.classList.remove('text-red-500');
            netDisplay.classList.add('text-green-600');
        } else if (gross > 0 || tare > 0) {
            netDisplay.innerText = "Error";
            netDisplay.classList.remove('text-green-600');
            netDisplay.classList.add('text-red-500');
        } else {
            netDisplay.innerText = "0.00";
            netDisplay.classList.remove('text-red-500');
            netDisplay.classList.add('text-green-600');
        }
    }

    grossInput.addEventListener('input', calculateNet);
    tareInput.addEventListener('input', calculateNet);
</script>
@endsection

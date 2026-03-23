<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - SJ{{ str_pad($logbook->id, 5, '0', STR_PAD_LEFT) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; color-adjust: exact; }
            .no-print { display: none !important; }
            @page { margin: 15mm; size: A4 portrait; }
        }
    </style>
</head>
<body class="bg-gray-100 text-black font-sans py-8 print:py-0 print:bg-white flex justify-center">

    <!-- Container Utama: Mirip Kertas A4 -->
    <div class="w-full max-w-[210mm] bg-white p-10 sm:p-12 shadow-xl border border-gray-300 print:shadow-none print:border-none print:p-0 print:m-0 relative">
        
        <!-- HEADER / KOP SURAT -->
        <div class="flex items-start justify-between border-b-[3px] border-black pb-5 mb-8">
            <div class="flex items-center space-x-5">
                <!-- Gunakan gambar logo asli jika ada -->
                @if(file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}" alt="Logo PAD" class="w-[85px] h-[85px] object-contain">
                @else
                    <!-- Fallback Logo Super Bersih -->
                    <div class="w-[85px] h-[85px] rounded-full border-[3px] border-[#002060] flex items-center justify-center bg-white">
                        <b class="text-2xl text-[#002060] tracking-tighter">PAD</b>
                    </div>
                @endif
                
                <div class="text-left text-black">
                    <h1 class="text-2xl md:text-[26px] font-bold text-[#002060] tracking-wide uppercase">PT. PRATAMA ANDAL DERMAGA</h1>
                    <div class="text-[14px] leading-tight mt-1.5 text-gray-900">
                        <p>Komp. Perkantoran Enggano Megah No. 9-I Lt. 2</p>
                        <p>Jl. Raya Enggano, Tanjung Priok, Jakarta 14310</p>
                        <p>pratamaandaldermaga@gmail.com - Telp. (021) 21697765</p>
                    </div>
                </div>
            </div>
            
            <div class="text-right pt-2">
                <h2 class="text-xl font-bold uppercase tracking-widest text-black border-2 border-black px-3 py-1.5 inline-block">Surat Jalan</h2>
                <div class="mt-3 text-sm text-black font-bold">No. SJ-{{ $logbook->created_at->format('ymd') }}-{{ str_pad($logbook->id, 4, '0', STR_PAD_LEFT) }}</div>
                <div class="text-xs text-gray-700 font-semibold mt-1">Tgl Terbit: {{ $logbook->created_at->format('d/m/Y') }}</div>
            </div>
        </div>

        <!-- BODY SURAT -->
        <div class="grid grid-cols-2 gap-x-8 gap-y-6 mb-8">
            
            <!-- INFORMASI PENGIRIMAN -->
            <div class="col-span-1">
                <h3 class="font-bold text-black uppercase border-b border-black pb-1.5 mb-3 text-sm tracking-wider">Informasi Kendaraan & Rute</h3>
                <table class="w-full text-xs leading-relaxed">
                    <tr><td class="py-1.5 align-top w-1/3 font-medium text-gray-700">Driver</td><td class="py-1.5 align-top font-bold text-black">: {{ $logbook->driver_name }}</td></tr>
                    <tr><td class="py-1.5 align-top font-medium text-gray-700">No. Polisi</td><td class="py-1.5 align-top font-bold text-black">: {{ strtoupper($logbook->license_plate) }}</td></tr>
                    <tr><td class="py-1.5 align-top font-medium text-gray-700">PIC / Penanggung</td><td class="py-1.5 align-top font-bold text-black">: {{ $logbook->pic_name }}</td></tr>
                    <tr><td class="py-1.5 align-top font-medium text-gray-700">Rute Asal</td><td class="py-1.5 align-top font-bold text-black">: {{ optional($logbook->route)->origin ?? '-' }}</td></tr>
                    <tr><td class="py-1.5 align-top font-medium text-gray-700">Rute Tujuan</td><td class="py-1.5 align-top font-bold text-black">: {{ optional($logbook->route)->destination ?? '-' }}</td></tr>
                </table>
            </div>

            <!-- INFORMASI MUATAN -->
            <div class="col-span-1">
                <h3 class="font-bold text-black uppercase border-b border-black pb-1.5 mb-3 text-sm tracking-wider">Spesifikasi Muatan</h3>
                <table class="w-full text-xs leading-relaxed">
                    <tr><td class="py-1.5 align-top w-1/3 font-medium text-gray-700">Importir</td><td class="py-1.5 align-top font-bold text-black">: {{ strtoupper(optional($logbook->supplier)->name ?? '-') }}</td></tr>
                    <tr><td class="py-1.5 align-top font-medium text-gray-700">Jenis Ternak</td><td class="py-1.5 align-top font-bold text-black">: SAPI {{ strtoupper(optional($logbook->cattleType)->name ?? '-') }}</td></tr>
                    <tr><td class="py-1.5 align-top font-medium text-gray-700">Jumlah Ekor</td><td class="py-1.5 align-top font-bold text-black">: {{ $logbook->headcount }} Ekor</td></tr>
                </table>
            </div>
            
            <!-- TIKET TIMBANG KOTAK TERTUTUP -->
            <div class="col-span-2 mt-4">
                <h3 class="font-bold text-black uppercase mb-2 text-sm tracking-wider">Hasil Timbang Kargo</h3>
                <div class="border-2 border-black">
                    <div class="grid grid-cols-3 divide-x-2 divide-black text-center text-sm">
                        <div class="py-3">
                            <div class="text-xs font-semibold text-gray-600 uppercase mb-1">Gross Weight</div>
                            <div class="font-bold text-lg text-black">{{ number_format($logbook->gross_weight, 2) }} KG</div>
                        </div>
                        <div class="py-3">
                            <div class="text-xs font-semibold text-gray-600 uppercase mb-1">Tare Weight</div>
                            <div class="font-bold text-lg text-black">{{ number_format($logbook->tare_weight, 2) }} KG</div>
                        </div>
                        <div class="py-3 bg-gray-100 print:bg-gray-100 print-exact">
                            <div class="text-xs font-bold text-black uppercase mb-1">Net Weight</div>
                            <div class="font-black text-xl text-black">{{ number_format($logbook->net_weight, 2) }} KG</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KEUANGAN SUPIR -->
            <div class="col-span-2 mt-4">
                <h3 class="font-bold text-black border-b border-black pb-1 mb-2 text-sm tracking-wider">Disbursement Operasional Driver</h3>
                <table class="w-full text-xs text-left mb-2">
                    <tr class="border-b border-gray-300 border-dashed">
                        <td class="py-2 text-gray-800">Uang Jalan Rute ({{ optional($logbook->route)->origin }} - {{ optional($logbook->route)->destination }})</td>
                        <td class="py-2 text-right font-bold text-black w-1/3">Rp {{ number_format(optional($logbook->route)->driver_money ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @if($logbook->additional_costs > 0)
                    <tr class="border-b border-gray-300 border-dashed">
                        <td class="py-2 text-gray-800">Tambahan (Susulan): <i class="text-gray-600">{{ $logbook->additional_costs_notes }}</i></td>
                        <td class="py-2 text-right font-bold text-black">Rp {{ number_format($logbook->additional_costs, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="py-3 font-bold text-sm text-black uppercase text-right pr-4">Total Diterima Driver:</td>
                        <td class="py-3 text-right font-black text-black text-base">Rp {{ number_format((optional($logbook->route)->driver_money ?? 0) + ($logbook->additional_costs ?? 0), 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

        </div>

        <!-- TANDA TANGAN -->
        <div class="mt-16 flex justify-between text-center text-xs font-bold text-black print:break-inside-avoid">
            <div class="w-1/3">
                <p class="mb-20">Admin Operasional</p>
                <p class="inline-block px-8 relative">
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-black"></span>
                    <span class="relative z-10 bottom-[-15px]">( {{ auth()->user()->name ?? 'Petugas Jaga' }} )</span>
                </p>
            </div>
            <div class="w-1/3 hidden sm:block"></div>
            <div class="w-1/3">
                <p class="mb-20">Supir / Penerima Mandat</p>
                <p class="inline-block px-8 relative">
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-black"></span>
                    <span class="relative z-10 bottom-[-15px]">( {{ $logbook->driver_name }} )</span>
                </p>
            </div>
        </div>

        <!-- Footer / Catatan Bawah Kertas -->
        <div class="mt-16 pt-3 border-t-2 border-black text-center">
            <p class="text-[10px] text-gray-600 italic">Dokumen ini dicetak otomatis oleh Sistem LogisFast PT PAD pada {{ date('d/m/Y H:i:s') }}. Dokumen ini sah sebagai bukti Muat & Timbang.</p>
        </div>

    </div>

    <!-- Tombol Aksi - Disembunyikan saat di Print -->
    <div class="fixed bottom-10 right-10 no-print flex space-x-4 z-50">
        <a href="{{ route('logbooks.index') }}" class="bg-gray-800 hover:bg-black shadow-2xl text-white font-bold py-3 px-6 rounded-full transition duration-300">Kembali</a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-800 shadow-2xl text-white font-bold py-3 px-6 rounded-full transition duration-300 flex items-center group">
            <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2-2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak (Print) Dokumen
        </button>
    </div>

</body>
</html>

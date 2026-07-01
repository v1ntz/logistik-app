<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Logbook - PT PAD</title>
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
                @if(file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}" alt="Logo PAD" class="w-[85px] h-[85px] object-contain">
                @else
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
                <h2 class="text-xl font-bold uppercase tracking-widest text-black border-2 border-black px-3 py-1.5 inline-block">Rekap Logbook</h2>
                <div class="mt-3 text-sm text-black font-bold">Laporan Timbangan</div>
                <div class="text-xs text-gray-700 font-semibold mt-1">Tgl Cetak: {{ date('d/m/Y') }}</div>
            </div>
        </div>

        <!-- 3-COLUMN METADATA (SAMA PERSIS DENGAN SURJAL) -->
        <div class="grid grid-cols-3 gap-x-6 gap-y-6 mb-8 border-b border-gray-300 pb-6">
            
            <!-- 1. KAPAL & BONGKAR -->
            <div class="col-span-1">
                <h3 class="font-black text-black uppercase border-b-2 border-black pb-1 mb-2.5 text-xs tracking-wider">1. Kapal & Bongkar</h3>
                <table class="w-full text-[11px] leading-relaxed">
                    <tr><td class="py-0.5 w-1/3 text-gray-600 font-medium">Kapal</td><td class="py-0.5 font-bold text-black">: {{ strtoupper($namaKapal) }}</td></tr>
                    <tr><td class="py-0.5 text-gray-600 font-medium">ETA</td><td class="py-0.5 font-bold text-black">: {{ strtoupper($eta) }}</td></tr>
                    <tr><td class="py-0.5 text-gray-600 font-medium">Kade</td><td class="py-0.5 font-bold text-black">: {{ strtoupper($kade) }}</td></tr>
                    <tr><td class="py-0.5 text-gray-600 font-medium">Party</td><td class="py-0.5 font-bold text-black">: {{ strtoupper($party) }}</td></tr>
                </table>
            </div>

            <!-- 2. SPESIFIKASI KARGO -->
            <div class="col-span-1">
                <h3 class="font-black text-black uppercase border-b-2 border-black pb-1 mb-2.5 text-xs tracking-wider">2. Spesifikasi Kargo</h3>
                <table class="w-full text-[11px] leading-relaxed">
                    <tr><td class="py-0.5 w-1/3 text-gray-600 font-medium">Consignee</td><td class="py-0.5 font-bold text-black">: {{ strtoupper($consignee) }}</td></tr>
                    <tr><td class="py-0.5 text-gray-600 font-medium">Tipe Sapi</td><td class="py-0.5 font-bold text-black">: SAPI {{ strtoupper($tipeSapi) }}</td></tr>
                </table>
            </div>

            <!-- 3. PERIODE LAPORAN -->
            <div class="col-span-1">
                <h3 class="font-black text-black uppercase border-b-2 border-black pb-1 mb-2.5 text-xs tracking-wider">3. Periode & Cetak</h3>
                <table class="w-full text-[11px] leading-relaxed">
                    <tr>
                        <td class="py-0.5 w-1/3 text-gray-600 font-medium">Periode</td>
                        <td class="py-0.5 font-bold text-black">: 
                            @if(request('start_date') && request('end_date'))
                                {{ date('d/m/y', strtotime(request('start_date'))) }} - {{ date('d/m/y', strtotime(request('end_date'))) }}
                            @else
                                Semua Data
                            @endif
                        </td>
                    </tr>
                    <tr><td class="py-0.5 text-gray-600 font-medium">Oleh</td><td class="py-0.5 font-bold text-black">: {{ auth()->user()->name ?? 'Petugas Jaga' }}</td></tr>
                </table>
            </div>

        </div>

        <!-- TABEL TIMBANGAN REKAP -->
        <div class="mb-8">
            <h3 class="font-bold text-black uppercase mb-3 text-xs tracking-wider">Daftar Hasil Timbangan Muatan</h3>
            <table class="w-full text-[10px] text-left border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="border border-gray-300 p-2 text-center font-bold text-black w-[4%]">NO</th>
                        <th class="border border-gray-300 p-2 text-center font-bold text-black w-[13%]">NO. POLISI</th>
                        <th class="border border-gray-300 p-2 text-center font-bold text-black w-[20%]">NOMOR SURAT</th>
                        <th class="border border-gray-300 p-2 text-center font-bold text-black w-[8%]">ISI (EKOR)</th>
                        <th class="border border-gray-300 p-2 text-right font-bold text-black w-[11%]">BRUTO (KG)</th>
                        <th class="border border-gray-300 p-2 text-right font-bold text-black w-[11%]">TARA (KG)</th>
                        <th class="border border-gray-300 p-2 text-right font-bold text-black w-[11%]">NETTO (KG)</th>
                        <th class="border border-gray-300 p-2 text-center font-bold text-black w-[10%]">JML EKOR</th>
                        <th class="border border-gray-300 p-2 text-right font-bold text-black w-[11%]">JML KG</th>
                        <th class="border border-gray-300 p-2 text-center font-bold text-black">KET</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalBrotto = 0;
                        $totalTarra = 0;
                        $totalNetto = 0;
                        $totalEkor = 0;
                    @endphp
                    @forelse($logbooks as $index => $log)
                        @php
                            $totalBrotto += $log->gross_weight;
                            $totalTarra += $log->tare_weight;
                            $totalNetto += $log->net_weight;
                            $totalEkor += $log->headcount;
                        @endphp
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="border border-gray-300 p-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 p-2 text-center font-semibold text-black">{{ strtoupper($log->license_plate) }}</td>
                            <td class="border border-gray-300 p-2 text-center">SJ-{{ $log->created_at->format('ymd') }}-{{ str_pad($log->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="border border-gray-300 p-2 text-center font-bold text-black">{{ $log->headcount }}</td>
                            <td class="border border-gray-300 p-2 text-right font-mono">{{ number_format($log->gross_weight, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 p-2 text-right font-mono">{{ number_format($log->tare_weight, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 p-2 text-right font-mono font-bold text-black">{{ number_format($log->net_weight, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 p-2 text-center">{{ $log->headcount }}</td>
                            <td class="border border-gray-300 p-2 text-right font-mono">{{ number_format($log->net_weight, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 p-2 text-center text-[9px] text-gray-500">{{ $log->additional_costs_notes ? 'Ada Tambahan' : '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="border border-gray-300 p-4 text-center text-gray-500 italic">Belum ada catatan muatan / timbangan.</td>
                        </tr>
                    @endforelse
                    
                    <!-- BARIS TOTAL -->
                    <tr class="bg-gray-150 border-t-2 border-black font-bold text-black">
                        <td colspan="3" class="border border-gray-300 p-2.5 text-center text-xs font-black uppercase">TOTAL</td>
                        <td class="border border-gray-300 p-2.5 text-center font-black text-[11px]">{{ $totalEkor }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono text-[11px]">{{ number_format($totalBrotto, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono text-[11px]">{{ number_format($totalTarra, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono font-black text-[11px]">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-center font-black text-[11px]">{{ $totalEkor }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono font-black text-[11px]">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SECTION TANDA TANGAN (SAMA PERSIS DENGAN SURJAL) -->
        <div class="mt-16 flex justify-between text-xs font-bold text-black print:break-inside-avoid relative">
            <div class="w-1/3 text-center relative">
                <p class="mb-20">{{ $lokasiTtdText }}<br>Admin Operasional,</p>
                
                <!-- Stempel Logo PAD Basah Transparan -->
                @if(file_exists(public_path('logo.png')))
                    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 opacity-75 pointer-events-none z-0">
                        <img src="{{ asset('logo.png') }}" alt="Stempel PAD" class="w-[70px] h-[70px] object-contain">
                    </div>
                @endif
                
                <p class="inline-block px-8 relative z-10">
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-black"></span>
                    <span class="relative z-10 bottom-[-15px]">( {{ strtoupper($namaTtd) }} )</span>
                </p>
            </div>
            <div class="w-1/3"></div>
            <div class="w-1/3 text-center">
                <p class="mb-20"><br>Penerima Laporan,</p>
                <p class="inline-block px-8 relative">
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-black"></span>
                    <span class="relative z-10 bottom-[-15px]">( ____________________ )</span>
                </p>
            </div>
        </div>

        <!-- FOOTER DOKUMEN (SAMA PERSIS DENGAN SURJAL) -->
        <div class="mt-20 pt-3 border-t-2 border-black text-center print:break-inside-avoid">
            <p class="text-[9px] text-gray-500 italic">Dokumen rekap ini dicetak otomatis oleh Sistem LogisFast PT PAD pada {{ date('d/m/Y H:i:s') }}. Dokumen ini sah sebagai bukti rekap timbangan.</p>
        </div>

    </div>

    <!-- TOMBOL AKSI - DISEMBUNYIKAN SAAT DI PRINT -->
    <div class="fixed bottom-10 right-10 no-print flex space-x-4 z-50">
        <button onclick="window.close()" class="bg-gray-800 hover:bg-black shadow-2xl text-white font-bold py-3 px-6 rounded-full transition duration-300">Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-800 shadow-2xl text-white font-bold py-3 px-6 rounded-full transition duration-300 flex items-center group">
            <svg class="w-5 h-5 mr-2 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak PDF / Print
        </button>
    </div>

    <script>
        // Otomatis cetak saat print dialog terbuka
        window.addEventListener('DOMContentLoaded', () => {
            if (!window.location.search.includes('noprint')) {
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>

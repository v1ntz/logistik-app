<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Logbook Bongkar Muat Sapi</title>
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

    <!-- Container Utama: Kertas A4 -->
    <div class="w-full max-w-[210mm] bg-white p-10 sm:p-12 shadow-xl border border-gray-300 print:shadow-none print:border-none print:p-0 print:m-0 relative">

        <div class="no-print text-right mb-6">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2.5 px-5 rounded-lg shadow-md transition duration-200 inline-flex items-center gap-2 text-xs">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan PDF
            </button>
        </div>

        <!-- KOP SURAT (Centered dengan Logo di Kiri) -->
        <div class="flex items-center border-b-[3px] border-black pb-5 mb-8">
            @if(file_exists(public_path('logo.png')))
                <img src="{{ asset('logo.png') }}" class="w-[80px] h-[80px] object-contain mr-5" alt="Logo PT PAD">
            @else
                <div class="w-[80px] h-[80px] rounded-full border-[3px] border-[#002060] flex items-center justify-center bg-white mr-5">
                    <b class="text-xl text-[#002060] tracking-tighter">PAD</b>
                </div>
            @endif
            <div class="flex-1 text-center pr-[80px]">
                <h1 class="text-2xl font-black text-[#002060] tracking-wide uppercase">PT. PRATAMA ANDAL DERMAGA</h1>
                <div class="text-[12px] font-bold text-gray-900 leading-tight mt-1">
                    <p>HEAD OPERATION : Komplek Perkantoran Enggano Megah No. 9-I Lt. 2</p>
                    <p>Kel. Tanjung Priok Kec. Tanjung Priok Jakarta Utara</p>
                    <p>TELP.(021) 21697765, FAX:(021) 21697765</p>
                </div>
            </div>
        </div>

        <!-- METADATA & BADGE TIPE SAPI (Kembali ke Tata Letak Pertama) -->
        <div class="flex justify-between items-start mb-6">
            <!-- Grid Informasi Kapal -->
            <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 border border-gray-200 rounded-lg p-4 flex-1 mr-6 text-xs">
                <div class="flex">
                    <span class="w-[100px] font-semibold text-gray-500 uppercase">Nama Kapal</span>
                    <span class="flex-1 font-bold text-black">: {{ strtoupper($namaKapal) }}</span>
                </div>
                <div class="flex">
                    <span class="w-[100px] font-semibold text-gray-500 uppercase">ETA</span>
                    <span class="flex-1 font-bold text-black">: {{ strtoupper($eta) }}</span>
                </div>
                <div class="flex">
                    <span class="w-[100px] font-semibold text-gray-500 uppercase">Kade</span>
                    <span class="flex-1 font-bold text-black">: {{ strtoupper($kade) }}</span>
                </div>
                <div class="flex">
                    <span class="w-[100px] font-semibold text-gray-500 uppercase">Consignee</span>
                    <span class="flex-1 font-bold text-black">: {{ strtoupper($consignee) }}</span>
                </div>
                <div class="flex col-span-2">
                    <span class="w-[100px] font-semibold text-gray-500 uppercase">Party</span>
                    <span class="flex-1 font-bold text-black">: {{ strtoupper($party) }}</span>
                </div>
            </div>
            
            <!-- Badge Tipe Sapi -->
            <div class="bg-gray-100 border border-gray-300 px-5 py-3.5 rounded-lg text-center font-black uppercase text-xs text-black tracking-wider self-center shadow-sm">
                {{ strtoupper($tipeSapi) }}
            </div>
        </div>

        <!-- TABEL TIMBANGAN REKAP -->
        <div class="mb-8">
            <table class="w-full text-[10px] text-left border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="border border-gray-300 p-2.5 text-center font-bold text-black w-[4%]">NO</th>
                        <th class="border border-gray-300 p-2.5 text-center font-bold text-black w-[13%]">NO. POLISI</th>
                        <th class="border border-gray-300 p-2.5 text-center font-bold text-black w-[20%]">NOMOR SURAT</th>
                        <th class="border border-gray-300 p-2.5 text-center font-bold text-black w-[8%]">ISI (EKOR)</th>
                        <th class="border border-gray-300 p-2.5 text-right font-bold text-black w-[11%]">BRUTO (KG)</th>
                        <th class="border border-gray-300 p-2.5 text-right font-bold text-black w-[11%]">TARA (KG)</th>
                        <th class="border border-gray-300 p-2.5 text-right font-bold text-black w-[11%]">NETTO (KG)</th>
                        <th class="border border-gray-300 p-2.5 text-center font-bold text-black w-[10%]">JML EKOR</th>
                        <th class="border border-gray-300 p-2.5 text-right font-bold text-black w-[11%]">JML KG</th>
                        <th class="border border-gray-300 p-2.5 text-center font-bold text-black">KET</th>
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
                            <td class="border border-gray-300 p-2 text-center"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="border border-gray-300 p-4 text-center text-gray-500 italic">Belum ada catatan muatan.</td>
                        </tr>
                    @endforelse
                    
                    <!-- BARIS TOTAL -->
                    <tr class="bg-gray-100 border-t-2 border-black font-bold text-black text-[11px]">
                        <td colspan="3" class="border border-gray-300 p-2.5 text-center font-black uppercase">TOTAL</td>
                        <td class="border border-gray-300 p-2.5 text-center font-black">{{ $totalEkor }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono">{{ number_format($totalBrotto, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono">{{ number_format($totalTarra, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono font-black">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-center font-black">{{ $totalEkor }}</td>
                        <td class="border border-gray-300 p-2.5 text-right font-mono font-black">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 p-2.5 text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TANDA TANGAN (Kembali ke versi pertama di sebelah kiri bawah) -->
        <div class="mt-12 flex justify-start text-xs font-bold text-black print:break-inside-avoid relative">
            <div class="w-[220px] relative">
                <p class="mb-16">{{ $lokasiTtdText }}<br>Admin Operasional,</p>
                
                <!-- Stempel Logo PAD Basah Transparan -->
                @if(file_exists(public_path('logo.png')))
                    <div class="absolute bottom-4 left-16 opacity-75 pointer-events-none z-0">
                        <img src="{{ asset('logo.png') }}" alt="Stempel PAD" class="w-[65px] h-[65px] object-contain">
                    </div>
                @endif
                
                <p class="inline-block px-6 relative z-10">
                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-black"></span>
                    <span class="relative z-10 bottom-[-15px]">( {{ strtoupper($namaTtd) }} )</span>
                </p>
            </div>
        </div>

    </div>

    <!-- TOMBOL AKSI -->
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

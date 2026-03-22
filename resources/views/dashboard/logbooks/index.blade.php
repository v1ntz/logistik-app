@extends('layouts.app')
@section('title', 'Logbook Bongkar Muat')
@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Data Logbook Operasional
    </h2>
    <div class="flex space-x-3 w-full sm:w-auto">
        <button id="btnExportExcel" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 shadow transition transform hover:-translate-y-0.5 rounded-lg flex items-center justify-center">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<script>
document.getElementById('btnExportExcel').addEventListener('click', async function() {
    const originalText = this.innerHTML;
    this.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyusun Excel...';
    this.disabled = true;

    try {
        const urlParams = new URLSearchParams(window.location.search);
        let exportUrl = '{{ route('logbooks.export') }}';
        if (urlParams.has('start_date') && urlParams.has('end_date')) {
            exportUrl += `?start_date=${urlParams.get('start_date')}&end_date=${urlParams.get('end_date')}`;
        }
        
        const response = await fetch(exportUrl);
        const logbooks = await response.json();

        const workbook = new ExcelJS.Workbook();
        workbook.creator = 'PT. Pratama Andal Dermaga';
        workbook.lastModifiedBy = 'PT. Pratama Andal Dermaga';
        workbook.created = new Date();
        workbook.modified = new Date();

        const worksheet = workbook.addWorksheet('Rekap Logbook', {views:[{state: 'frozen', ySplit: 1}]});

        worksheet.columns = [
            { header: 'ID', key: 'id', width: 10 },
            { header: 'Tanggal', key: 'date', width: 22 },
            { header: 'Driver', key: 'driver', width: 25 },
            { header: 'Nopol', key: 'nopol', width: 15 },
            { header: 'PIC Driver', key: 'pic', width: 20 },
            { header: 'Importir', key: 'supplier', width: 25 },
            { header: 'Jenis Sapi', key: 'sapi', width: 20 },
            { header: 'Status', key: 'status', width: 15 },
            { header: 'Headcount', key: 'headcount', width: 15 },
            { header: 'Gross (KG)', key: 'gross', width: 18 },
            { header: 'Tare (KG)', key: 'tare', width: 18 },
            { header: 'Net (KG)', key: 'net', width: 18 },
            { header: 'Base Uang Jalan', key: 'base', width: 22 },
            { header: 'Biaya Ekstra', key: 'extra', width: 20 },
            { header: 'Keterangan Ekstra', key: 'notes', width: 30 },
            { header: 'Total Uang', key: 'total', width: 22 },
        ];

        worksheet.getRow(1).font = { bold: true, color: { argb: 'FFFFFFFF' }, size: 12 };
        worksheet.getRow(1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF16A34A' } };
        worksheet.getRow(1).alignment = { vertical: 'middle', horizontal: 'center' };

        logbooks.forEach(log => {
            const supplierName = log.supplier ? log.supplier.name : '-';
            const sapiName = (log.cattleType || log.cattle_type) ? (log.cattleType || log.cattle_type).name : '-';
            const baseRoute = log.route ? parseFloat(log.route.driver_money) : 0;
            const extra = parseFloat(log.additional_costs || 0);
            
            worksheet.addRow({
                id: log.id,
                date: new Date(log.created_at).toLocaleString('id-ID'),
                driver: log.driver_name,
                nopol: log.license_plate,
                pic: log.pic_name,
                supplier: supplierName,
                sapi: sapiName,
                status: log.status,
                headcount: log.headcount || 0,
                gross: log.gross_weight ? parseFloat(log.gross_weight) : 0,
                tare: log.tare_weight ? parseFloat(log.tare_weight) : 0,
                net: log.net_weight ? parseFloat(log.net_weight) : 0,
                base: baseRoute,
                extra: extra,
                notes: log.additional_costs_notes || '-',
                total: baseRoute + extra,
            });
        });

        worksheet.autoFilter = 'A1:P1';
        worksheet.eachRow((row, rowNumber) => {
            if(rowNumber > 1) {
                row.getCell('base').numFmt = '"Rp" #,##0';
                row.getCell('extra').numFmt = '"Rp" #,##0';
                row.getCell('total').numFmt = '"Rp" #,##0';
                row.getCell('gross').numFmt = '#,##0.00 "KG"';
                row.getCell('tare').numFmt = '#,##0.00 "KG"';
                row.getCell('net').numFmt = '#,##0.00 "KG"';
            }
        });

        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        
        const startDate = urlParams.get('start_date') || 'ALL';
        const endDate = urlParams.get('end_date') || 'ALL';
        a.download = `Rekap_Logistik_Sapi_${startDate}_to_${endDate}.xlsx`;
        
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);

    } catch (error) {
        console.error('Failed to generate Excel:', error);
        alert('Gagal mendownload file Excel. Data tidak tersedia atau terjadi kesalahan sistem.');
    } finally {
        this.innerHTML = originalText;
        this.disabled = false;
    }
});
</script>
@endsection

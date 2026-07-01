<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Route;
use App\Models\CattleType;
use App\Models\Supplier;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LogbookController extends Controller
{
    public function index(Request $request) {
        $query = Logbook::with(['route', 'cattleType', 'supplier'])->latest();

        if ($request->has('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $logbooks = $query->get();

        // Ambil logbook terbaru yang memiliki data kapal untuk auto-fill modal export
        $latestWithShipment = Logbook::with('cattleType')
            ->whereNotNull('nama_kapal')
            ->latest()
            ->first();

        // Mengambil semua kapal untuk rekap manifest di modal export
        $kapals = \App\Models\Kapal::with(['manifests.importir'])->latest()->get();

        return view('dashboard.logbooks.index', compact('logbooks', 'latestWithShipment', 'kapals'));
    }


    public function create() {
        $routes = Route::all();
        $kapals = \App\Models\Kapal::with(['manifests.importir'])->latest()->get();
        return view('dashboard.logbooks.create', compact('routes', 'kapals'));
    }

    public function store(Request $request) {
        $request->validate([
            'driver_name' => 'required',
            'license_plate' => 'required',
            'route_id' => 'required',
            'pic_name' => 'required'
        ]);

        $data = $request->only(['driver_name', 'license_plate', 'route_id', 'pic_name']);
        $data['kapal_manifest_id'] = $request->filled('kapal_manifest_id') ? $request->kapal_manifest_id : null;
        $data['status'] = 'Muat';

        if ($request->filled('kapal_manifest_id')) {
            $manifest = \App\Models\KapalManifest::with('kapal')->find($request->kapal_manifest_id);
            if ($manifest) {
                $data['supplier_id'] = $manifest->importir_id; // Importir lokal dipetakan ke supplier_id
                $data['exporter_id'] = $manifest->exporter_id; // Eksportir asing dipetakan ke exporter_id
                $data['consignee']   = $manifest->consignee;
                $data['kade']        = $manifest->kade;
                $data['party']       = $manifest->party;
                $data['nama_kapal']  = $manifest->kapal?->nama_kapal;
                $data['eta']         = $manifest->kapal?->eta;
            }
        }

        Logbook::create($data);
        return redirect()->route('logbooks.index')->with('success', 'Catatan muat ditambahkan.');
    }

    public function edit(Logbook $logbook) {
        $cattleTypes = CattleType::all();
        $suppliers = Supplier::all();
        $exporters = \App\Models\Exporter::all();
        return view('dashboard.logbooks.edit', compact('logbook', 'cattleTypes', 'suppliers', 'exporters'));
    }

    public function update(Request $request, Logbook $logbook) {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'cattle_type_id' => 'required|exists:cattle_types,id',
            'exporter_id' => 'nullable|exists:exporters,id',
            'headcount' => 'required|integer|min:1',
            'gross_weight' => 'required|numeric|min:0',
            'tare_weight' => 'required|numeric|min:0',
            'additional_costs' => 'nullable|numeric|min:0',
            'additional_costs_notes' => 'nullable|string',
            'nama_kapal' => 'nullable|string',
            'eta' => 'nullable|string',
            'kade' => 'nullable|string',
            'consignee' => 'nullable|string',
            'party' => 'nullable|string'
        ]);
        
        $net_weight = $request->gross_weight - $request->tare_weight;
        
        $updateData = [
            'supplier_id' => $request->supplier_id,
            'cattle_type_id' => $request->cattle_type_id,
            'exporter_id' => $request->filled('exporter_id') ? $request->exporter_id : null,
            'headcount' => $request->headcount,
            'gross_weight' => $request->gross_weight,
            'tare_weight' => $request->tare_weight,
            'net_weight' => $net_weight,
            'additional_costs' => $request->additional_costs ?? 0,
            'additional_costs_notes' => $request->additional_costs_notes,
            'status' => 'Selesai'
        ];

        // Lindungi data kapal agar tidak tertimpa null saat confirm timbangan
        if ($request->filled('nama_kapal')) $updateData['nama_kapal'] = $request->nama_kapal;
        if ($request->filled('eta')) $updateData['eta'] = $request->eta;
        if ($request->filled('kade')) $updateData['kade'] = $request->kade;
        if ($request->filled('consignee')) $updateData['consignee'] = $request->consignee;
        if ($request->filled('party')) $updateData['party'] = $request->party;

        $logbook->update($updateData);
        return redirect()->route('logbooks.index')->with('success', 'Penimbangan selesai.');
    }

    public function print(Logbook $logbook) {
        $logbook->load(['kapalManifest.kapal', 'kapalManifest.importir', 'route', 'cattleType', 'supplier']);
        $ongoingHeadcount = 0;
        $manifest = $logbook->kapalManifest;

        if ($manifest) {
            // Hitung dari semua logbook yang pakai manifest yang sama
            $ongoingHeadcount = Logbook::where('kapal_manifest_id', $manifest->id)
                ->where('id', '<=', $logbook->id)
                ->sum('headcount');
        } elseif (!empty($logbook->nama_kapal)) {
            // Fallback ke cara lama (nama_kapal)
            $ongoingHeadcount = Logbook::where('nama_kapal', $logbook->nama_kapal)
                ->where('id', '<=', $logbook->id)
                ->sum('headcount');
        } else {
            $ongoingHeadcount = $logbook->headcount;
        }

        return view('dashboard.logbooks.print', compact('logbook', 'ongoingHeadcount'));
    }

    public function destroy(Logbook $logbook) {
        $logbook->delete();
        return redirect()->route('logbooks.index')->with('success', 'Data logbook berhasil dihapus sementara (arsip).');
    }

    public function restore($id) {
        $logbook = Logbook::withTrashed()->findOrFail($id);
        $logbook->restore();
        return redirect()->route('logbooks.index')->with('success', 'Data logbook berhasil dikembalikan dari arsip.');
    }

    public function export(Request $request) {
        $query = Logbook::with(['route', 'cattleType', 'supplier', 'kapalManifest.kapal', 'kapalManifest.importir'])->latest();

        if ($request->filled('kapal_manifest_id')) {
            $query->where('kapal_manifest_id', $request->kapal_manifest_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date') && !$request->filled('kapal_manifest_id')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $logbooks = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Kolom Lebar
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(25);

        // KOP SURAT
        $sheet->mergeCells('C1:J1');
        $sheet->setCellValue('C1', 'PT. PRATAMA ANDAL DERMAGA');
        $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('C2:J2');
        $sheet->setCellValue('C2', 'HEAD OPERATION : Komplek Perkantoran Enggano Megah No. 9-I Lt. 2');
        $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('C3:J3');
        $sheet->setCellValue('C3', 'Kel. Tanjung Priok Kec. Tanjung Priok Jakarta Utara');
        $sheet->getStyle('C3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('C4:J4');
        $sheet->setCellValue('C4', 'TELP.(021)21697765, FAX:(021)21697765');
        $sheet->getStyle('C4')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        if (file_exists(public_path('logo.png'))) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setPath(public_path('logo.png'));
            $drawing->setHeight(80);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(20);
            $drawing->setOffsetY(10);
            $drawing->setWorksheet($sheet);
        }

        // Ambil data default dari manifest atau logbook pertama
        $firstLogbook = $logbooks->first();
        
        $selectedManifest = null;
        if ($request->filled('kapal_manifest_id')) {
            $selectedManifest = \App\Models\KapalManifest::with('kapal', 'importir')->find($request->kapal_manifest_id);
        } elseif ($firstLogbook && $firstLogbook->kapalManifest) {
            $selectedManifest = $firstLogbook->kapalManifest;
        }

        $defaultNamaKapal = $selectedManifest?->kapal?->nama_kapal ?? $firstLogbook->nama_kapal ?? '';
        $defaultEta = $selectedManifest?->kapal?->eta ?? $firstLogbook->eta ?? '';
        $defaultKade = $selectedManifest?->kade ?? $firstLogbook->kade ?? '';
        $defaultConsignee = $selectedManifest?->consignee ?? $firstLogbook->consignee ?? '';
        $defaultParty = $selectedManifest?->party ? $selectedManifest->party . ' EKOR' : ($firstLogbook->party ?? '');
        $defaultTipeSapi = optional($firstLogbook?->cattleType)->name ?? '';

        // METADATA KAPAL DLL (Digeser ke Kolom B agar tidak terpotong oleh lebar Kolom A yang sempit)
        $sheet->setCellValue('B6', 'NAMA KAPAL'); $sheet->setCellValue('C6', ':'); $sheet->setCellValue('D6', strtoupper($request->input('nama_kapal', $defaultNamaKapal)));
        $sheet->setCellValue('B7', 'ETA');        $sheet->setCellValue('C7', ':'); $sheet->setCellValue('D7', strtoupper($request->input('eta', $defaultEta)));
        $sheet->setCellValue('B8', 'KADE');       $sheet->setCellValue('C8', ':'); $sheet->setCellValue('D8', strtoupper($request->input('kade', $defaultKade)));
        $sheet->setCellValue('B9', 'CONSIGNEE');  $sheet->setCellValue('C9', ':'); $sheet->setCellValue('D9', strtoupper($request->input('consignee', $defaultConsignee)));
        $sheet->setCellValue('B10', 'PARTY');     $sheet->setCellValue('C10', ':'); $sheet->setCellValue('D10', strtoupper($request->input('party', $defaultParty)));
        
        $sheet->mergeCells('I10:J10');
        $sheet->setCellValue('I10', strtoupper($request->input('tipe_sapi', $defaultTipeSapi)));
        $sheet->getStyle('I10:J10')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('I10:J10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        // Header Table
        $headers = ['NO', 'NO.POLISI', 'NOMOR SURAT', 'ISI', 'BROTTO', 'TARRA', 'NETTO', "JUMLAH\nEKOR", "JUMLAH\nKG", 'KET'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '11', $header);
            $col++;
        }

        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A11:J11')->applyFromArray($headerStyle);
        $sheet->getRowDimension(11)->setRowHeight(30);

        // Data Rows
        $row = 12;
        $no = 1;
        $totalBrotto = 0; $totalTarra = 0; $totalNetto = 0; $totalEkor = 0;

        foreach ($logbooks as $logbook) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, strtoupper($logbook->license_plate));
            $sheet->setCellValue('C' . $row, 'SJ-' . $logbook->created_at->format('ymd') . '-' . str_pad($logbook->id, 4, '0', STR_PAD_LEFT));
            $sheet->setCellValue('D' . $row, $logbook->headcount);
            $sheet->setCellValue('E' . $row, $logbook->gross_weight);
            $sheet->setCellValue('F' . $row, $logbook->tare_weight);
            $sheet->setCellValue('G' . $row, $logbook->net_weight);
            $sheet->setCellValue('H' . $row, $logbook->headcount);
            $sheet->setCellValue('I' . $row, $logbook->net_weight);
            $sheet->setCellValue('J' . $row, ''); // KET dikosongkan karena untuk catatan sapi sakit/mati

            $totalBrotto += $logbook->gross_weight;
            $totalTarra += $logbook->tare_weight;
            $totalNetto += $logbook->net_weight;
            $totalEkor += $logbook->headcount;

            $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A'.$row.':D'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E'.$row.':I'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $row++;
        }

        // Add 3 empty rows minimally for look
        $emptyTarget = max(5, $logbooks->count() < 10 ? 10 - $logbooks->count() : 3);
        for ($i=0; $i<$emptyTarget; $i++) {
            $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
        }

        // Total Row
        $sheet->setCellValue('D' . $row, 'TOTAL');
        $sheet->setCellValue('E' . $row, $totalBrotto);
        $sheet->setCellValue('F' . $row, $totalTarra);
        $sheet->setCellValue('G' . $row, $totalNetto);
        $sheet->setCellValue('H' . $row, $totalEkor);
        $sheet->setCellValue('I' . $row, $totalNetto);
        $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D'.$row.':J'.$row)->getFont()->setBold(true);

        $sheet->getStyle('A11:J'.$row)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK);

        // Tanda Tangan
        $sigRow = $row + 3;
        $lokasiInput = $request->input('lokasi_ttd');
        if (empty($lokasiInput)) {
            $lokasiTtdText = 'Tanjung Priok, ' . date('d F Y');
        } else {
            $lokasiTtdText = $lokasiInput;
        }
        $sheet->setCellValue('A' . $sigRow, $lokasiTtdText);

        if (file_exists(public_path('logo.png'))) {
            $drawing2 = new Drawing();
            $drawing2->setName('Signature Stamp');
            $drawing2->setPath(public_path('logo.png'));
            $drawing2->setHeight(65);
            $drawing2->setCoordinates('B' . ($sigRow + 1));
            $drawing2->setOffsetX(10);
            $drawing2->setOffsetY(5);
            $drawing2->setWorksheet($sheet);
        }

        $sheet->setCellValue('A' . ($sigRow + 6), strtoupper($request->input('nama_ttd', 'LIAN')));
        $sheet->getStyle('A' . ($sigRow + 6))->getFont()->setBold(true);

        // Save & Download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Rekap_Logbook_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}

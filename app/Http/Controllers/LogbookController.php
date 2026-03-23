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
        return view('dashboard.logbooks.index', compact('logbooks'));
    }

    public function create() {
        $routes = Route::all();
        return view('dashboard.logbooks.create', compact('routes'));
    }

    public function store(Request $request) {
        $request->validate(['driver_name' => 'required', 'license_plate' => 'required', 'route_id' => 'required', 'pic_name' => 'required']);
        Logbook::create(array_merge($request->all(), ['status' => 'Muat']));
        return redirect()->route('logbooks.index')->with('success', 'Catatan muat ditambahkan.');
    }

    public function edit(Logbook $logbook) {
        $cattleTypes = CattleType::all();
        $suppliers = Supplier::all();
        return view('dashboard.logbooks.edit', compact('logbook', 'cattleTypes', 'suppliers'));
    }

    public function update(Request $request, Logbook $logbook) {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'cattle_type_id' => 'required|exists:cattle_types,id',
            'headcount' => 'required|integer|min:1',
            'gross_weight' => 'required|numeric|min:0',
            'tare_weight' => 'required|numeric|min:0',
            'additional_costs' => 'nullable|numeric|min:0',
            'additional_costs_notes' => 'nullable|string'
        ]);
        
        $net_weight = $request->gross_weight - $request->tare_weight;
        $logbook->update([
            'supplier_id' => $request->supplier_id,
            'cattle_type_id' => $request->cattle_type_id,
            'headcount' => $request->headcount,
            'gross_weight' => $request->gross_weight,
            'tare_weight' => $request->tare_weight,
            'net_weight' => $net_weight,
            'additional_costs' => $request->additional_costs ?? 0,
            'additional_costs_notes' => $request->additional_costs_notes,
            'status' => 'Selesai'
        ]);
        return redirect()->route('logbooks.index')->with('success', 'Penimbangan selesai.');
    }

    public function print(Logbook $logbook) {
        return view('dashboard.logbooks.print', compact('logbook'));
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
        $query = Logbook::with(['route', 'cattleType', 'supplier'])->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
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

        // METADATA KAPAL DLL
        $sheet->setCellValue('A6', 'NAMA KAPAL'); $sheet->setCellValue('B6', ':'); $sheet->setCellValue('C6', strtoupper($request->input('nama_kapal', '')));
        $sheet->setCellValue('A7', 'ETA');        $sheet->setCellValue('B7', ':'); $sheet->setCellValue('C7', strtoupper($request->input('eta', '')));
        $sheet->setCellValue('A8', 'KADE');       $sheet->setCellValue('B8', ':'); $sheet->setCellValue('C8', strtoupper($request->input('kade', '')));
        $sheet->setCellValue('A9', 'CONSIGNEE');  $sheet->setCellValue('B9', ':'); $sheet->setCellValue('C9', strtoupper($request->input('consignee', '')));
        $sheet->setCellValue('A10', 'PARTY');     $sheet->setCellValue('B10', ':'); $sheet->setCellValue('C10', strtoupper($request->input('party', '')));
        
        $sheet->mergeCells('I10:J10');
        $sheet->setCellValue('I10', strtoupper($request->input('tipe_sapi', '')));
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
            $sheet->setCellValue('D' . $row, strtoupper(optional($logbook->cattleType)->name ?? ''));
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
            $drawing2->setName('Signature');
            $drawing2->setPath(public_path('logo.png'));
            $drawing2->setHeight(70);
            $drawing2->setCoordinates('A' . ($sigRow + 1));
            $drawing2->setOffsetX(20);
            $drawing2->setOffsetY(5);
            $drawing2->setWorksheet($sheet);
        }

        $sheet->setCellValue('A' . ($sigRow + 7), strtoupper($request->input('nama_ttd', 'LIAN')));
        $sheet->getStyle('A' . ($sigRow + 7))->getFont()->setBold(true);

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

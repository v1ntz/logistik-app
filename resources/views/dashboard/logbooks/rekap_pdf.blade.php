<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Logbook Bongkar Muat Sapi</title>
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm;
            }
            body {
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: #000;
                background: #fff;
                font-size: 11px;
                line-height: 1.4;
            }
            .no-print {
                display: none;
            }
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            font-size: 11px;
        }
        .header-container {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-img {
            width: 75px;
            height: auto;
            margin-right: 20px;
        }
        .company-info {
            flex: 1;
            text-align: center;
        }
        .company-name {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 1px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }
        .company-detail {
            font-size: 10px;
            font-weight: bold;
            color: #555;
            margin: 0 0 2px 0;
        }
        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
        }
        .metadata-item {
            display: flex;
            margin-bottom: 4px;
        }
        .metadata-label {
            width: 100px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            font-size: 10px;
        }
        .metadata-value {
            flex: 1;
            font-weight: bold;
            color: #0f172a;
        }
        .type-sap-badge {
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
            border-radius: 6px;
            text-align: center;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 11px;
            align-self: center;
            justify-self: end;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #f1f5f9;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #94a3b8;
            padding: 8px 4px;
            font-size: 9px;
            text-align: center;
        }
        td {
            border: 1px solid #94a3b8;
            padding: 6px 6px;
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
            font-family: monospace;
            font-size: 11px;
        }
        .total-row {
            font-weight: bold;
            background: #f8fafc;
        }
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            position: relative;
        }
        .signature-box {
            width: 220px;
            position: relative;
        }
        .sig-date {
            margin-bottom: 60px;
            font-weight: bold;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 11px;
        }
        .stamp-container {
            position: absolute;
            left: 80px;
            top: 15px;
            opacity: 0.85;
            z-index: -1;
        }
        .stamp-img {
            width: 70px;
            height: auto;
        }
        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="btn-print">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan PDF
        </button>
    </div>

    <!-- KOP SURAT -->
    <div class="header-container">
        <img src="{{ asset('logo.png') }}" class="logo-img" alt="Logo PT PAD">
        <div class="company-info">
            <h1 class="company-name">PT. PRATAMA ANDAL DERMAGA</h1>
            <p class="company-detail">HEAD OPERATION : Komplek Perkantoran Enggano Megah No. 9-I Lt. 2</p>
            <p class="company-detail">Kel. Tanjung Priok Kec. Tanjung Priok Jakarta Utara</p>
            <p class="company-detail">TELP.(021) 21697765, FAX:(021) 21697765</p>
        </div>
    </div>

    <!-- METADATA -->
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
        <div class="metadata-grid" style="flex: 1; margin-right: 20px;">
            <div class="metadata-item">
                <span class="metadata-label">Nama Kapal</span>
                <span class="metadata-value">: {{ strtoupper($namaKapal) }}</span>
            </div>
            <div class="metadata-item">
                <span class="metadata-label">ETA</span>
                <span class="metadata-value">: {{ strtoupper($eta) }}</span>
            </div>
            <div class="metadata-item">
                <span class="metadata-label">Kade</span>
                <span class="metadata-value">: {{ strtoupper($kade) }}</span>
            </div>
            <div class="metadata-item">
                <span class="metadata-label">Consignee</span>
                <span class="metadata-value">: {{ strtoupper($consignee) }}</span>
            </div>
            <div class="metadata-item" style="grid-column: span 2;">
                <span class="metadata-label">Party</span>
                <span class="metadata-value">: {{ strtoupper($party) }}</span>
            </div>
        </div>
        <div class="type-sap-badge">
            {{ strtoupper($tipeSapi) }}
        </div>
    </div>

    <!-- TABEL REKAP -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 12%;">NO. POLISI</th>
                <th style="width: 20%;">NOMOR SURAT</th>
                <th style="width: 8%;">ISI (EKOR)</th>
                <th style="width: 10%;">BROTTO (KG)</th>
                <th style="width: 10%;">TARRA (KG)</th>
                <th style="width: 10%;">NETTO (KG)</th>
                <th style="width: 10%;">JUMLAH EKOR</th>
                <th style="width: 10%;">JUMLAH KG</th>
                <th>KET</th>
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
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ strtoupper($log->license_plate) }}</td>
                    <td class="text-center">SJ-{{ $log->created_at->format('ymd') }}-{{ str_pad($log->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $log->headcount }}</td>
                    <td class="text-right">{{ number_format($log->gross_weight, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($log->tare_weight, 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($log->net_weight, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $log->headcount }}</td>
                    <td class="text-right">{{ number_format($log->net_weight, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #666;">Belum ada data timbangan.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="3" class="text-center" style="text-transform: uppercase; font-size: 10px; font-weight: 950;">TOTAL</td>
                <td class="text-center">{{ $totalEkor }}</td>
                <td class="text-right">{{ number_format($totalBrotto, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalTarra, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                <td class="text-center">{{ $totalEkor }}</td>
                <td class="text-right">{{ number_format($totalNetto, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="signature-container">
        <div class="signature-box">
            <p class="sig-date">{{ $lokasiTtdText }}</p>
            
            <div class="stamp-container">
                <img src="{{ asset('logo.png') }}" class="stamp-img" alt="Stempel PT PAD">
            </div>
            
            <p class="sig-name">{{ strtoupper($namaTtd) }}</p>
        </div>
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

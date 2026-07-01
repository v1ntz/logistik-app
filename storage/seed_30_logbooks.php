<?php

use App\Models\Logbook;
use App\Models\KapalManifest;
use App\Models\Route as ExpedisiRoute;
use App\Models\CattleType;
use App\Models\Supplier;
use App\Models\Exporter;

// Pastikan Bootstrap Laravel ter-load jika dijalankan manual
// Tapi karena kita akan panggil via Tinker, models sudah ter-load otomatis.

// 1. Dapatkan atau buat Manifest Kapal
$manifest = KapalManifest::latest()->first();
if (!$manifest) {
    // Buat kapal baru
    $kapal = \App\Models\Kapal::create([
        'nama_kapal' => 'MV. OCEANIC BREEDER',
        'eta' => '05-JULI-2026'
    ]);
    
    // Dapatkan supplier/importir
    $importir = Supplier::first();
    if (!$importir) {
        $importir = Supplier::create(['name' => 'PT. INDO PRIMA MEAT']);
    }
    
    // Dapatkan exporter
    $exporter = Exporter::first();
    if (!$exporter) {
        $exporter = Exporter::create(['name' => 'ELDERS AUSTRALIA']);
    }

    $manifest = KapalManifest::create([
        'kapal_id' => $kapal->id,
        'importir_id' => $importir->id,
        'exporter_id' => $exporter->id,
        'kade' => '102',
        'consignee' => 'PT. CINTA ASIH FARM',
        'party' => 500
    ]);
}

// 2. Dapatkan data pendukung
$route = ExpedisiRoute::first();
if (!$route) {
    $route = ExpedisiRoute::create([
        'origin' => 'TJ. PRIOK',
        'destination' => 'SUBANG',
        'driver_money' => 1200000
    ]);
}

$cattleType = CattleType::first();
if (!$cattleType) {
    $cattleType = CattleType::create(['name' => 'FDR BULL']);
}

$supplier = Supplier::first();

// 3. Loop buat 30 data timbangan selesai
$start_time = now()->subHours(5);
$drivers = ['AGUS', 'BUDI', 'DEDI', 'IWAN', 'JOKO', 'RUDI', 'SLAMET', 'WIBOWO', 'YADI', 'HENDRA'];

echo "Mulai menyuntik 30 data logbook...\n";

for ($i = 1; $i <= 30; $i++) {
    $headcount = rand(10, 18);
    $gross = rand(15200, 16000);
    $tare = rand(8500, 9600);
    $net = $gross - $tare;
    
    $created = $start_time->copy()->addMinutes($i * 8);

    Logbook::create([
        'driver_name' => $drivers[array_rand($drivers)] . ' ' . $i,
        'license_plate' => 'B ' . rand(1000, 9999) . ' ' . chr(rand(65, 90)) . chr(rand(65, 90)),
        'route_id' => $route->id,
        'pic_name' => 'JAGA SHIFT',
        'status' => 'Selesai',
        'supplier_id' => $manifest->importir_id,
        'exporter_id' => $manifest->exporter_id,
        'cattle_type_id' => $cattleType->id,
        'headcount' => $headcount,
        'gross_weight' => $gross,
        'tare_weight' => $tare,
        'net_weight' => $net,
        'additional_costs' => rand(0, 3) * 10000, // 0k, 10k, 20k, 30k
        'additional_costs_notes' => rand(0, 1) ? 'Beli Tol / Rokok supir' : null,
        'kapal_manifest_id' => $manifest->id,
        'nama_kapal' => $manifest->kapal?->nama_kapal,
        'eta' => $manifest->kapal?->eta,
        'kade' => $manifest->kade,
        'consignee' => $manifest->consignee,
        'party' => $manifest->party . ' EKOR',
        'created_at' => $created,
        'updated_at' => $created
    ]);
}

echo "Selesai! 30 data timbangan berhasil disuntik ke manifest kapal: " . $manifest->kapal?->nama_kapal . "\n";

<?php

namespace Database\Seeders;

use App\Models\CattleType;
use App\Models\Exporter;
use App\Models\Kapal;
use App\Models\KapalManifest;
use App\Models\Logbook;
use App\Models\Route;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── ADMIN USER ───────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@pad.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // ─── JENIS TERNAK ─────────────────────────────────────────
        $cattleTypes = collect([
            'Sapi Bali',
            'Sapi Brahman',
            'Sapi Limousin',
            'Kerbau',
            'Kambing',
        ])->map(fn($name) => CattleType::updateOrCreate(['name' => $name]));

        // ─── RUTE ─────────────────────────────────────────────────
        $routes = collect([
            ['origin' => 'Makassar',  'destination' => 'Jakarta',    'driver_money' => 750000],
            ['origin' => 'Makassar',  'destination' => 'Surabaya',   'driver_money' => 500000],
            ['origin' => 'Makassar',  'destination' => 'Balikpapan', 'driver_money' => 600000],
            ['origin' => 'Makassar',  'destination' => 'Manado',     'driver_money' => 650000],
            ['origin' => 'Makassar',  'destination' => 'Ambon',      'driver_money' => 700000],
        ])->map(fn($r) => Route::updateOrCreate(
            ['origin' => $r['origin'], 'destination' => $r['destination']],
            ['driver_money' => $r['driver_money']]
        ));

        // ─── SUPPLIER ─────────────────────────────────────────────
        $suppliers = collect([
            ['name' => 'PT Ternak Makmur',       'location' => 'Gowa, Sulsel'],
            ['name' => 'CV Sapi Jaya',            'location' => 'Bone, Sulsel'],
            ['name' => 'UD Peternakan Nusantara', 'location' => 'Barru, Sulsel'],
            ['name' => 'PT Agro Ternak Sulsel',   'location' => 'Pinrang, Sulsel'],
            ['name' => 'Koperasi Peternak Maju',  'location' => 'Pangkep, Sulsel'],
        ])->map(fn($s) => Supplier::updateOrCreate(['name' => $s['name']], ['location' => $s['location']]));

        // ─── EKSPORTIR ────────────────────────────────────────────
        $exporters = collect([
            ['name' => 'PT Ekspor Ternak Indonesia', 'location' => 'Jakarta Utara'],
            ['name' => 'CV Mitra Ekspor Nusantara',  'location' => 'Surabaya'],
            ['name' => 'PT Global Livestock Export', 'location' => 'Makassar'],
        ])->map(fn($e) => Exporter::updateOrCreate(['name' => $e['name']], ['location' => $e['location']]));

        // ─── KAPAL ────────────────────────────────────────────────
        $kapals = collect([
            ['nama_kapal' => 'KM Dobonsolo',    'eta' => '2026-11-05'],
            ['nama_kapal' => 'KM Tilong Kabila', 'eta' => '2026-11-12'],
            ['nama_kapal' => 'KM Labobar',       'eta' => '2026-11-20'],
            ['nama_kapal' => 'KM Umsini',        'eta' => '2026-12-01'],
        ])->map(fn($k) => Kapal::updateOrCreate(['nama_kapal' => $k['nama_kapal']], ['eta' => $k['eta']]));

        // ─── MANIFEST KAPAL ───────────────────────────────────────
        $manifests = collect([
            ['kapal_id' => $kapals[0]->id, 'importir_id' => $suppliers[0]->id, 'exporter_id' => $exporters[0]->id, 'kade' => '3', 'consignee' => 'PT Daging Segar Jakarta',  'party' => 150],
            ['kapal_id' => $kapals[0]->id, 'importir_id' => $suppliers[1]->id, 'exporter_id' => $exporters[1]->id, 'kade' => '5', 'consignee' => 'CV Makmur Surabaya',        'party' => 100],
            ['kapal_id' => $kapals[1]->id, 'importir_id' => $suppliers[2]->id, 'exporter_id' => $exporters[0]->id, 'kade' => '2', 'consignee' => 'PT Pangan Lestari',         'party' => 200],
            ['kapal_id' => $kapals[2]->id, 'importir_id' => $suppliers[3]->id, 'exporter_id' => $exporters[2]->id, 'kade' => '7', 'consignee' => 'UD Sumber Ternak Kalimantan','party' => 80],
            ['kapal_id' => $kapals[3]->id, 'importir_id' => $suppliers[4]->id, 'exporter_id' => $exporters[1]->id, 'kade' => '4', 'consignee' => 'PT Ambon Sejahtera',         'party' => 120],
        ])->map(fn($m) => KapalManifest::create($m));

        // ─── 30 DATA LOGBOOK ──────────────────────────────────────
        $logbookData = [
            // Manifest 1 — KM Dobonsolo (10 data)
            ['manifest' => 0, 'status' => 'selesai',  'headcount' => 15, 'gross' => 9500,  'tare' => 2200, 'driver' => 'Baharuddin',  'plate' => 'DD 1234 AB', 'route' => 0, 'cattle' => 0, 'supplier' => 0, 'date' => '2026-10-01'],
            ['manifest' => 0, 'status' => 'selesai',  'headcount' => 12, 'gross' => 7800,  'tare' => 2100, 'driver' => 'Kamaruddin',  'plate' => 'DD 5678 BC', 'route' => 1, 'cattle' => 1, 'supplier' => 1, 'date' => '2026-10-02'],
            ['manifest' => 0, 'status' => 'selesai',  'headcount' => 18, 'gross' => 11200, 'tare' => 2400, 'driver' => 'Sudirman',    'plate' => 'DD 9012 CD', 'route' => 0, 'cattle' => 0, 'supplier' => 2, 'date' => '2026-10-03'],
            ['manifest' => 0, 'status' => 'selesai',  'headcount' => 10, 'gross' => 6300,  'tare' => 1900, 'driver' => 'Hasanuddin',  'plate' => 'DD 3456 DE', 'route' => 2, 'cattle' => 2, 'supplier' => 0, 'date' => '2026-10-04'],
            ['manifest' => 0, 'status' => 'dalam_proses', 'headcount' => 14, 'gross' => 8900, 'tare' => 2300, 'driver' => 'Ruslan', 'plate' => 'DD 7890 EF', 'route' => 1, 'cattle' => 0, 'supplier' => 3, 'date' => '2026-10-05'],
            ['manifest' => 0, 'status' => 'dalam_proses', 'headcount' => 16, 'gross' => 10100, 'tare' => 2200, 'driver' => 'Mansur', 'plate' => 'DD 1357 FG', 'route' => 0, 'cattle' => 1, 'supplier' => 4, 'date' => '2026-10-06'],
            ['manifest' => 0, 'status' => 'menunggu',  'headcount' => 20, 'gross' => 12500, 'tare' => 2600, 'driver' => 'Abdullah',   'plate' => 'DD 2468 GH', 'route' => 3, 'cattle' => 0, 'supplier' => 0, 'date' => '2026-10-07'],
            ['manifest' => 0, 'status' => 'menunggu',  'headcount' => 8,  'gross' => 5200,  'tare' => 1800, 'driver' => 'Ramli',      'plate' => 'DD 3579 HI', 'route' => 4, 'cattle' => 3, 'supplier' => 1, 'date' => '2026-10-08'],
            ['manifest' => 0, 'status' => 'menunggu',  'headcount' => 11, 'gross' => 7000,  'tare' => 2000, 'driver' => 'Zulkifli',   'plate' => 'DD 4680 IJ', 'route' => 2, 'cattle' => 4, 'supplier' => 2, 'date' => '2026-10-09'],
            ['manifest' => 0, 'status' => 'dibatalkan','headcount' => 13, 'gross' => 8400,  'tare' => 2100, 'driver' => 'Arifuddin',  'plate' => 'DD 5791 JK', 'route' => 1, 'cattle' => 2, 'supplier' => 3, 'date' => '2026-10-10'],

            // Manifest 2 — KM Dobonsolo (5 data)
            ['manifest' => 1, 'status' => 'selesai',  'headcount' => 17, 'gross' => 10800, 'tare' => 2300, 'driver' => 'Nurdin',     'plate' => 'DD 6802 KL', 'route' => 0, 'cattle' => 1, 'supplier' => 1, 'date' => '2026-10-03'],
            ['manifest' => 1, 'status' => 'selesai',  'headcount' => 9,  'gross' => 5800,  'tare' => 1900, 'driver' => 'Saharuddin', 'plate' => 'DD 7913 LM', 'route' => 1, 'cattle' => 0, 'supplier' => 2, 'date' => '2026-10-04'],
            ['manifest' => 1, 'status' => 'selesai',  'headcount' => 22, 'gross' => 13800, 'tare' => 2700, 'driver' => 'Mappinawang','plate' => 'DD 8024 MN', 'route' => 0, 'cattle' => 1, 'supplier' => 4, 'date' => '2026-10-05'],
            ['manifest' => 1, 'status' => 'dalam_proses', 'headcount' => 11, 'gross' => 7200, 'tare' => 2050, 'driver' => 'Idrus', 'plate' => 'DD 9135 NO', 'route' => 2, 'cattle' => 2, 'supplier' => 0, 'date' => '2026-10-06'],
            ['manifest' => 1, 'status' => 'menunggu',  'headcount' => 14, 'gross' => 9000,  'tare' => 2200, 'driver' => 'Mustafa',   'plate' => 'DD 0246 OP', 'route' => 3, 'cattle' => 3, 'supplier' => 3, 'date' => '2026-10-07'],

            // Manifest 3 — KM Tilong Kabila (5 data)
            ['manifest' => 2, 'status' => 'selesai',  'headcount' => 25, 'gross' => 15600, 'tare' => 3000, 'driver' => 'Syarifuddin','plate' => 'DD 1357 PQ', 'route' => 0, 'cattle' => 0, 'supplier' => 2, 'date' => '2026-10-08'],
            ['manifest' => 2, 'status' => 'selesai',  'headcount' => 19, 'gross' => 12000, 'tare' => 2500, 'driver' => 'Irwansyah',  'plate' => 'DD 2468 QR', 'route' => 4, 'cattle' => 1, 'supplier' => 0, 'date' => '2026-10-09'],
            ['manifest' => 2, 'status' => 'dalam_proses', 'headcount' => 23, 'gross' => 14400, 'tare' => 2800, 'driver' => 'Faisal', 'plate' => 'DD 3579 RS', 'route' => 1, 'cattle' => 0, 'supplier' => 1, 'date' => '2026-10-10'],
            ['manifest' => 2, 'status' => 'menunggu',  'headcount' => 16, 'gross' => 10200, 'tare' => 2300, 'driver' => 'Sarif',     'plate' => 'DD 4680 ST', 'route' => 2, 'cattle' => 2, 'supplier' => 4, 'date' => '2026-10-11'],
            ['manifest' => 2, 'status' => 'menunggu',  'headcount' => 13, 'gross' => 8100,  'tare' => 2000, 'driver' => 'Amirullah', 'plate' => 'DD 5791 TU', 'route' => 3, 'cattle' => 3, 'supplier' => 3, 'date' => '2026-10-12'],

            // Manifest 4 — KM Labobar (5 data)
            ['manifest' => 3, 'status' => 'selesai',  'headcount' => 10, 'gross' => 6500,  'tare' => 1950, 'driver' => 'Heriyanto',  'plate' => 'DD 6802 UV', 'route' => 2, 'cattle' => 1, 'supplier' => 0, 'date' => '2026-10-05'],
            ['manifest' => 3, 'status' => 'selesai',  'headcount' => 7,  'gross' => 4600,  'tare' => 1700, 'driver' => 'Wahyudi',    'plate' => 'DD 7913 VW', 'route' => 3, 'cattle' => 0, 'supplier' => 2, 'date' => '2026-10-06'],
            ['manifest' => 3, 'status' => 'dalam_proses', 'headcount' => 12, 'gross' => 7800, 'tare' => 2100, 'driver' => 'Firmansyah', 'plate' => 'DD 8024 WX', 'route' => 4, 'cattle' => 2, 'supplier' => 3, 'date' => '2026-10-07'],
            ['manifest' => 3, 'status' => 'menunggu',  'headcount' => 9,  'gross' => 5900,  'tare' => 1850, 'driver' => 'Amiruddin', 'plate' => 'DD 9135 XY', 'route' => 0, 'cattle' => 4, 'supplier' => 4, 'date' => '2026-10-08'],
            ['manifest' => 3, 'status' => 'dibatalkan','headcount' => 15, 'gross' => 9700,  'tare' => 2250, 'driver' => 'Lukmanul',  'plate' => 'DD 0246 YZ', 'route' => 1, 'cattle' => 3, 'supplier' => 1, 'date' => '2026-10-09'],

            // Manifest 5 — KM Umsini (5 data)
            ['manifest' => 4, 'status' => 'selesai',  'headcount' => 20, 'gross' => 12800, 'tare' => 2550, 'driver' => 'Ridwan',     'plate' => 'DD 1234 ZA', 'route' => 4, 'cattle' => 0, 'supplier' => 3, 'date' => '2026-10-10'],
            ['manifest' => 4, 'status' => 'selesai',  'headcount' => 18, 'gross' => 11400, 'tare' => 2400, 'driver' => 'Anwar',      'plate' => 'DD 2345 AB', 'route' => 0, 'cattle' => 1, 'supplier' => 4, 'date' => '2026-10-11'],
            ['manifest' => 4, 'status' => 'dalam_proses', 'headcount' => 21, 'gross' => 13200, 'tare' => 2650, 'driver' => 'Burhanuddin', 'plate' => 'DD 3456 BC', 'route' => 3, 'cattle' => 2, 'supplier' => 0, 'date' => '2026-10-12'],
            ['manifest' => 4, 'status' => 'menunggu',  'headcount' => 11, 'gross' => 7100,  'tare' => 2000, 'driver' => 'Rahmat',    'plate' => 'DD 4567 CD', 'route' => 2, 'cattle' => 0, 'supplier' => 1, 'date' => '2026-10-13'],
            ['manifest' => 4, 'status' => 'menunggu',  'headcount' => 14, 'gross' => 8900,  'tare' => 2200, 'driver' => 'Syamsuddin','plate' => 'DD 5678 DE', 'route' => 1, 'cattle' => 4, 'supplier' => 2, 'date' => '2026-10-14'],
        ];

        foreach ($logbookData as $d) {
            $grossW = $d['gross'];
            $tareW  = $d['tare'];
            $netW   = $grossW - $tareW;
            $manifest = $manifests[$d['manifest']];

            Logbook::create([
                'status'                  => $d['status'],
                'headcount'               => $d['headcount'],
                'gross_weight'            => $grossW,
                'tare_weight'             => $tareW,
                'net_weight'              => $netW,
                'driver_name'             => $d['driver'],
                'license_plate'           => $d['plate'],
                'route_id'                => $routes[$d['route']]->id,
                'cattle_type_id'          => $cattleTypes[$d['cattle']]->id,
                'supplier_id'             => $suppliers[$d['supplier']]->id,
                'exporter_id'             => $manifest->exporter_id,
                'kapal_manifest_id'       => $manifest->id,
                'nama_kapal'              => $manifest->kapal->nama_kapal,
                'eta'                     => $manifest->kapal->eta,
                'kade'                    => $manifest->kade,
                'consignee'               => $manifest->consignee,
                'party'                   => $manifest->party,
                'pic_name'                => 'Admin PAD',
                'additional_costs'        => rand(0, 1) ? rand(50000, 500000) : 0,
                'additional_costs_notes'  => rand(0, 1) ? 'Biaya tambahan bongkar muat' : null,
                'created_at'              => $d['date'] . ' 08:00:00',
                'updated_at'              => $d['date'] . ' 08:00:00',
            ]);
        }

        $this->command->info('✅ Seeder selesai: 30 logbook, 5 rute, 5 supplier, 3 eksportir, 4 kapal, 5 manifest.');
    }
}

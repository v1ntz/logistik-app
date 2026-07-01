<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('kapal_manifests', 'exporter_id')) {
            Schema::table('kapal_manifests', function (Blueprint $table) {
                $table->foreignId('exporter_id')->nullable()->constrained('exporters')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kapal_manifests', 'exporter_id')) {
            Schema::table('kapal_manifests', function (Blueprint $table) {
                $table->dropForeign(['exporter_id']);
                $table->dropColumn('exporter_id');
            });
        }
    }
};

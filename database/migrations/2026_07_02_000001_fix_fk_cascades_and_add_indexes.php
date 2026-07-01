<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix route_id FK: CASCADE -> SET NULL
        Schema::table('logbooks', function (Blueprint $table) {
            // Make column nullable first if not already
            try {
                $table->unsignedBigInteger('route_id')->nullable()->change();
            } catch (\Exception $e) {}
        });
        
        Schema::table('logbooks', function (Blueprint $table) {
            try {
                $table->dropForeign(['route_id']);
            } catch (\Exception $e) {
                // FK might not exist or have different name
                try {
                    $table->dropForeign('logbooks_route_id_foreign');
                } catch (\Exception $e2) {}
            }
        });
        
        Schema::table('logbooks', function (Blueprint $table) {
            $table->foreign('route_id')->references('id')->on('routes')->nullOnDelete();
        });

        // Fix importir_id FK on kapal_manifests: CASCADE -> SET NULL  
        Schema::table('kapal_manifests', function (Blueprint $table) {
            try {
                $table->unsignedBigInteger('importir_id')->nullable()->change();
            } catch (\Exception $e) {}
        });
        
        Schema::table('kapal_manifests', function (Blueprint $table) {
            try {
                $table->dropForeign(['importir_id']);
            } catch (\Exception $e) {
                try {
                    $table->dropForeign('kapal_manifests_importir_id_foreign');
                } catch (\Exception $e2) {}
            }
        });
        
        Schema::table('kapal_manifests', function (Blueprint $table) {
            $table->foreign('importir_id')->references('id')->on('suppliers')->nullOnDelete();
        });

        // Add indexes for performance
        Schema::table('logbooks', function (Blueprint $table) {
            try { $table->index('status'); } catch (\Exception $e) {}
            try { $table->index('created_at'); } catch (\Exception $e) {}
            try { $table->index('nama_kapal'); } catch (\Exception $e) {}
        });
    }

    public function down(): void
    {
        // Revert indexes
        Schema::table('logbooks', function (Blueprint $table) {
            try { $table->dropIndex(['status']); } catch (\Exception $e) {}
            try { $table->dropIndex(['created_at']); } catch (\Exception $e) {}
            try { $table->dropIndex(['nama_kapal']); } catch (\Exception $e) {}
        });
    }
};

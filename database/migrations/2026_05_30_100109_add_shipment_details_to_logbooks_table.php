<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->string('nama_kapal')->nullable();
            $table->string('eta')->nullable();
            $table->string('kade')->nullable();
            $table->string('consignee')->nullable();
            $table->string('party')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn(['nama_kapal', 'eta', 'kade', 'consignee', 'party']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kapal_manifests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapal_id')->constrained('kapals')->onDelete('cascade');
            $table->foreignId('importir_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('exporter_id')->nullable()->constrained('exporters')->onDelete('set null');
            $table->string('kade')->nullable();
            $table->string('consignee')->nullable();
            $table->integer('party')->nullable()->comment('Total ekor untuk importir ini di kapal ini');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kapal_manifests');
    }
};

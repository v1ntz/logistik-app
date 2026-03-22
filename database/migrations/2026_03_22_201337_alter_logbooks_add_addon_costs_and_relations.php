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
            $table->foreignId('cattle_type_id')->nullable()->constrained('cattle_types')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->decimal('additional_costs', 15, 2)->default(0);
            $table->string('additional_costs_notes')->nullable();
            
            // Try to drop the old string column if it exists and SQLite allows
            if (Schema::hasColumn('logbooks', 'cattle_type')) {
                $table->dropColumn('cattle_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

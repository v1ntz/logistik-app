<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('petugas')->after('email');
        });

        // Set admin@pad.com sebagai admin
        DB::table('users')->where('email', 'admin@pad.com')->update(['role' => 'admin']);

        // Jaga-jaga jika user pertama dibuat dengan email berbeda
        $firstUser = DB::table('users')->orderBy('id', 'asc')->first();
        if ($firstUser) {
            DB::table('users')->where('id', $firstUser->id)->update(['role' => 'admin']);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};

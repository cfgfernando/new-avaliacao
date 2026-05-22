<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')
                ->where('title', 'Avaliações (APD)')
                ->update(['url' => '/evaluations']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')
                ->where('title', 'Avaliações (APD)')
                ->update(['url' => '/dashboard']);
        }
    }
};

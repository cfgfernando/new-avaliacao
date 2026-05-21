<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('status', 30)->default('draft')->change();
            $table->enum('categoria', ['geral', 'saude', 'guarda', 'educacao'])->nullable()->after('evaluated_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->change();
            $table->dropColumn('categoria');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_diary_incidents', function (Blueprint $table) {
            $table->string('evidence_file')->nullable()->after('incident_date')->comment('Caminho do arquivo de evidência (Storage)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_diary_incidents', function (Blueprint $table) {
            $table->dropColumn('evidence_file');
        });
    }
};

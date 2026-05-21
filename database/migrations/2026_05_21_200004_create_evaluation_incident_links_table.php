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
        Schema::create('evaluation_incident_links', function (Blueprint $table) {
            $table->foreignId('evaluation_answer_id')->constrained('evaluation_answers')->onDelete('cascade');
            $table->foreignId('diary_incident_id')->constrained('employee_diary_incidents')->onDelete('cascade');
            
            $table->primary(['evaluation_answer_id', 'diary_incident_id'], 'eval_inc_link_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_incident_links');
    }
};

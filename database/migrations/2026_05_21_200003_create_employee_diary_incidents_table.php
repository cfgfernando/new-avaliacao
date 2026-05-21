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
        Schema::create('employee_diary_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->string('category', 50)->comment('Categoria associada (ex: cooperacao, iniciativa, etc.)');
            $table->text('description')->comment('Descrição detalhada do incidente crítico');
            $table->enum('type', ['positive', 'negative'])->comment('Tipo do incidente (Positivo ou Negativo)');
            $table->date('incident_date')->comment('Data em que o incidente ocorreu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_diary_incidents');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cycle_id')->constrained('evaluation_cycles')->cascadeOnDelete();
            $table->integer('faltas_injustificadas')->default(0);
            $table->integer('atrasos_minutos')->default(0);
            $table->integer('horas_extras')->default(0);
            $table->string('mensagem')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'cycle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_points');
    }
};

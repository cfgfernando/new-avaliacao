<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('critical_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('evaluation_answers')->onDelete('cascade');
            $table->text('justification');
            $table->string('evidence_path', 255)->comment('Caminho do arquivo PDF/JPG anexado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('critical_incidents');
    }
};

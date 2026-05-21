<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->date('start_date');
            $table->date('end_date');
            $table->json('weights')->comment('Pesos das categorias ex: {"assiduidade": 2, "disciplina": 1, ...}');
            $table->decimal('cutoff_score', 4, 2)->default(3.00);
            $table->enum('status', ['draft', 'active', 'suspended', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_cycles');
    }
};

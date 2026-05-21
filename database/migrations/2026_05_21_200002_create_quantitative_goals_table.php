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
        Schema::create('quantitative_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->string('description', 255)->comment('Descrição da meta quantitativa');
            $table->string('metric', 100)->comment('Unidade de medida ou indicador');
            $table->decimal('target_value', 12, 2)->comment('Valor alvo pactuado');
            $table->decimal('achieved_value', 12, 2)->nullable()->comment('Valor efetivamente alcançado');
            $table->decimal('weight', 4, 2)->default(1.00)->comment('Peso da meta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quantitative_goals');
    }
};

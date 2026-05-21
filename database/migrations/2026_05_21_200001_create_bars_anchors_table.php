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
        Schema::create('bars_anchors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('evaluation_questions')->onDelete('cascade');
            $table->tinyInteger('score')->comment('Nota correspondente de 1 a 5');
            $table->text('behavioral_description')->comment('Descrição comportamental ancorada');
            $table->timestamps();

            $table->unique(['question_id', 'score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bars_anchors');
    }
};

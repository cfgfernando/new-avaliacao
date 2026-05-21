<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('evaluation_questions')->onDelete('cascade');
            $table->tinyInteger('score')->comment('Nota de 1 a 5');
            $table->timestamps();

            $table->unique(['evaluation_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_answers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('category', [
                'assiduidade',
                'disciplina',
                'iniciativa',
                'responsabilidade',
                'cooperacao',
                'qualidade',
                'desenvolvimento_rh',
                'avaliacao_usuario'
            ]);
            $table->enum('group_type', ['geral', 'saude', 'guarda', 'educacao'])->default('geral');
            $table->text('text');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['group_type', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};

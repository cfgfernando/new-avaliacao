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
        Schema::table('evaluations', function (Blueprint $table) {
            $table->decimal('weight_goals', 4, 2)->default(0.50)->after('final_score')->comment('Peso das metas quantitativas');
            $table->decimal('weight_competencies', 4, 2)->default(0.50)->after('weight_goals')->comment('Peso das competências BARS');
            $table->decimal('score_goals', 4, 2)->nullable()->after('weight_competencies')->comment('Nota do bloco de metas quantitativas');
            $table->decimal('score_competencies', 4, 2)->nullable()->after('score_goals')->comment('Nota do bloco de competências BARS');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['weight_goals', 'weight_competencies', 'score_goals', 'score_competencies']);
        });
    }
};

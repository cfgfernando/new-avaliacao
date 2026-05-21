<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('registration_number', 50)->nullable()->unique()->after('email');
            $table->string('cargo', 100)->nullable()->after('registration_number');
            $table->string('lotacao', 100)->nullable()->after('cargo');
            $table->enum('evaluation_group', ['geral', 'saude', 'guarda', 'educacao'])
                  ->default('geral')
                  ->after('lotacao');
            $table->foreignId('evaluator_id')->nullable()->after('evaluation_group')
                  ->constrained('users')->nullOnDelete();
            $table->boolean('has_active_pad')->default(false)->after('evaluator_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['evaluator_id']);
            $table->dropColumn([
                'registration_number',
                'cargo',
                'lotacao',
                'evaluation_group',
                'evaluator_id',
                'has_active_pad'
            ]);
        });
    }
};

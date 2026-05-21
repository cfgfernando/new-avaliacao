<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('evaluation_cycles')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evaluated_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'submitted', 'under_appeal', 'completed'])->default('pending');
            $table->decimal('final_score', 4, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['cycle_id', 'evaluated_id'], 'unique_cycle_evaluated');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};

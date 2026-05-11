<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cells', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->foreignId('node_id')
                  ->constrained('hierarchy_nodes')
                  ->restrictOnDelete()
                  ->comment('Nó hierárquico (Setor) ao qual esta célula pertence');
            $table->foreignId('leader_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Líder responsável pela célula');
            $table->enum('meeting_day', [
                'Sunday', 'Monday', 'Tuesday', 'Wednesday',
                'Thursday', 'Friday', 'Saturday'
            ]);
            $table->time('meeting_time')->nullable();
            $table->text('address')->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('node_id');
            $table->index('leader_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cells');
    }
};

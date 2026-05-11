<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 20)->nullable();
            $table->string('email', 180)->nullable();
            $table->foreignId('assigned_cell_id')
                  ->nullable()
                  ->constrained('cells')
                  ->nullOnDelete()
                  ->comment('Célula que recebeu / acompanha o visitante');
            $table->enum('status', ['New', 'Returning', 'Interested', 'Converted', 'Inactive'])
                  ->default('New');
            $table->timestamp('last_contact_at')->nullable();
            $table->foreignId('contacted_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Líder responsável pelo contato');
            $table->text('notes')->nullable();
            $table->string('how_did_you_know')->nullable()->comment('Como conheceu a célula');
            $table->timestamps();
            $table->softDeletes();

            $table->index('assigned_cell_id');
            $table->index('status');
            $table->index('last_contact_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};

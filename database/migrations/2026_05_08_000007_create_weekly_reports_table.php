<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cell_id')
                  ->constrained('cells')
                  ->restrictOnDelete()
                  ->comment('Célula que gerou o relatório');
            $table->date('report_date')->comment('Data do culto/reunião de célula');

            // --- Presença ---
            $table->unsignedSmallInteger('present_members')->default(0)->comment('Membros presentes');
            $table->unsignedSmallInteger('visitors')->default(0)->comment('Visitantes adultos');
            $table->unsignedSmallInteger('children')->default(0)->comment('Crianças presentes');

            // --- Indicadores MDA ---
            $table->unsignedSmallInteger('mda_count')->default(0)
                  ->comment('Participantes no ciclo de discipulado MDA');
            $table->unsignedSmallInteger('conversions')->default(0)
                  ->comment('Conversões na semana');

            // --- Ação Social ---
            $table->decimal('kg_social', 8, 2)->default(0)
                  ->comment('Kg de alimentos arrecadados para ação social');

            // --- Financeiro (pré-consolidação) ---
            $table->decimal('offer_pix', 12, 2)->default(0)
                  ->comment('Ofertas recebidas via PIX');
            $table->decimal('offer_cash', 12, 2)->default(0)
                  ->comment('Ofertas recebidas em dinheiro');

            // --- Workflow ---
            $table->enum('status', ['Draft', 'Submitted', 'Conciliated'])
                  ->default('Draft')
                  ->comment('Draft=Rascunho, Submitted=Enviado, Conciliated=Conciliado pelo Tesoureiro');
            $table->foreignId('submitted_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('conciliated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('conciliated_at')->nullable();

            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Integridade: um único relatório por célula por data
            $table->unique(['cell_id', 'report_date'], 'unique_cell_report_date');

            $table->index('status');
            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};

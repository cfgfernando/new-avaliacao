<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->comment('Vínculo 1:1 com o usuário do sistema');
            $table->foreignId('mentor_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Discipulador/Mentor no modelo MDA');
            $table->date('conversion_date')->nullable();
            $table->date('baptism_date')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('cpf', 14)->nullable()->unique()->comment('CPF para emissão de recibos (LGPD)');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Transferred', 'Deceased'])
                  ->default('Active');
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('mentor_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

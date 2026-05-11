<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NOTA: Esta migration substitui a migration padrão do Laravel.
// Remova ou renomeie o arquivo padrão 'create_users_table.php' antes de rodar.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 180)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['Admin', 'Treasurer', 'Supervisor', 'Leader'])
                  ->default('Leader')
                  ->comment('RBAC: Admin=Rede, Treasurer=Tesoureiro, Supervisor=Área/Setor, Leader=Célula');
            $table->boolean('active')->default(true);
            // cell_id será adicionado via migration 000004 após criar a tabela cells
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

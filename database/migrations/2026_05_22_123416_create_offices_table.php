<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Criar a tabela offices
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('sigla', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Adicionar a coluna office_id na tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('office_id')->nullable()->after('lotacao')
                  ->constrained('offices')->nullOnDelete();
        });

        // 3. Migrar dados existentes da coluna lotacao para offices
        $lotacoes = DB::table('users')
            ->whereNotNull('lotacao')
            ->where('lotacao', '!=', '')
            ->distinct()
            ->pluck('lotacao');

        foreach ($lotacoes as $nomeLotacao) {
            // Obter ou criar o Office
            $officeId = DB::table('offices')->insertGetId([
                'name' => $nomeLotacao,
                'sigla' => $this->gerarSigla($nomeLotacao),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Associar os usuários
            DB::table('users')
                ->where('lotacao', $nomeLotacao)
                ->update(['office_id' => $officeId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropColumn('office_id');
        });

        Schema::dropIfExists('offices');
    }

    private function gerarSigla(string $nome): string
    {
        $palavras = explode(' ', $nome);
        $sigla = '';
        $ignoradas = ['de', 'da', 'do', 'e', 'em', 'para', 'o', 'a', 'os', 'as'];

        foreach ($palavras as $palavra) {
            $p = mb_strtolower($palavra);
            if (in_array($p, $ignoradas) || empty($p)) {
                continue;
            }
            $sigla .= mb_strtoupper(mb_substr($p, 0, 1));
        }

        return $sigla ?: mb_strtoupper(mb_substr($nome, 0, 3));
    }
};

<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\WeeklyReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountingService
{
    /**
     * Gera o lançamento contábil de um Relatório Semanal (Ofertas).
     * 
     * Regra: Partida Dobrada.
     * Crédito: Receita (Ofertas de Célula)
     * Débito: Ativo (Caixa/Banco)
     */
    public function createFromWeeklyReport(WeeklyReport $report, User $user): JournalEntry
    {
        return DB::transaction(function () use ($report, $user) {
            // 1. Localizar as contas (em um sistema real, estas IDs estariam em config ou settings)
            // Aqui buscamos pelo código padrão NBC sugerido na migration
            $revenueAccount = Account::where('code', 'LIKE', '4%')->where('is_analytical', true)->first(); // Ex: 4.1.01...
            $assetAccount   = Account::where('code', 'LIKE', '1.1.01%')->where('is_analytical', true)->first(); // Ex: 1.1.01 (Caixa)

            if (!$revenueAccount || !$assetAccount) {
                throw new \Exception("Contas contábeis para ofertas não configuradas no Plano de Contas.");
            }

            $totalAmount = $report->total_offer;

            // 2. Criar o Cabeçalho (JournalEntry)
            $entry = JournalEntry::create([
                'reference'       => 'CELL-' . $report->id . '-' . now()->format('YmdHis'),
                'entry_date'      => $report->report_date,
                'description'     => "Oferta Célula: {$report->cell->name} - Ref: {$report->report_date->format('d/m/Y')}",
                'source_type'     => WeeklyReport::class,
                'source_id'       => $report->id,
                'created_by'      => $user->id,
            ]);

            // 3. Linha de CRÉDITO (Receita aumenta por crédito)
            $entry->lines()->create([
                'account_id' => $revenueAccount->id,
                'type'       => 'Credit',
                'amount'     => $totalAmount,
                'description'=> "Receita de ofertas da célula",
            ]);

            // 4. Linha de DÉBITO (Ativo aumenta por débito)
            $entry->lines()->create([
                'account_id' => $assetAccount->id,
                'type'       => 'Debit',
                'amount'     => $totalAmount,
                'description'=> "Entrada em caixa/banco das ofertas",
            ]);

            // 5. Atualizar Saldos das Contas (Simplificado)
            $revenueAccount->increment('current_balance', $totalAmount);
            $assetAccount->increment('current_balance', $totalAmount);

            return $entry;
        });
    }
}

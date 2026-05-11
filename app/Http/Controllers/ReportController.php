<?php

namespace App\Http\Controllers;

use App\Models\JournalEntryLine;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Gera a Demonstração do Resultado do Exercício (DRE) em PDF.
     */
    public function dre(Request $request)
    {
        $this->authorize('view-reports', User::class); // Supõe um gate ou permissão de Admin/Tesoureiro

        $year = $request->get('year', date('Y'));

        // 1. Agregação de Receitas por Mês
        $revenues = JournalEntryLine::query()
            ->join('accounts', 'journal_entry_lines.account_id', '=', 'accounts.id')
            ->join('journal_entries', 'journal_entry_lines.journal_entry_id', '=', 'journal_entries.id')
            ->where('accounts.type', 'Revenue')
            ->whereYear('journal_entries.entry_date', $year)
            ->select(
                DB::raw('MONTH(journal_entries.entry_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // 2. Agregação de Despesas por Mês
        $expenses = JournalEntryLine::query()
            ->join('accounts', 'journal_entry_lines.account_id', '=', 'accounts.id')
            ->join('journal_entries', 'journal_entry_lines.journal_entry_id', '=', 'journal_entries.id')
            ->where('accounts.type', 'Expense')
            ->whereYear('journal_entries.entry_date', $year)
            ->select(
                DB::raw('MONTH(journal_entries.entry_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // 3. Montar Estrutura de Dados Mensal
        $data = [];
        $totalYearRevenue = 0;
        $totalYearExpense = 0;

        for ($m = 1; $m <= 12; $m++) {
            $rev = $revenues->get($m)->total ?? 0;
            $exp = $expenses->get($m)->total ?? 0;
            
            $data[$m] = [
                'month_name' => $this->getMonthName($m),
                'revenue'    => $rev,
                'expense'    => $exp,
                'result'     => $rev - $exp
            ];

            $totalYearRevenue += $rev;
            $totalYearExpense += $exp;
        }

        $totals = [
            'revenue' => $totalYearRevenue,
            'expense' => $totalYearExpense,
            'result'  => $totalYearRevenue - $totalYearExpense
        ];

        // 4. Detalhamento por Conta (Sintético)
        $accountDetails = Account::whereIn('type', ['Revenue', 'Expense'])
            ->where('current_balance', '>', 0)
            ->orderBy('code')
            ->get();

        // 5. Gerar PDF
        $pdf = Pdf::loadView('reports.dre-pdf', [
            'year'           => $year,
            'data'           => $data,
            'totals'         => $totals,
            'accountDetails' => $accountDetails,
            'generated_at'   => now()->format('d/m/Y H:i')
        ]);

        return $pdf->setPaper('a4')->stream("DRE-{$year}.pdf");
    }

    private function getMonthName($month)
    {
        return [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ][$month];
    }
}

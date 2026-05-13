<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Finance\JournalEntry;
use App\Models\Finance\AccountingAudit;
use App\Models\Finance\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualEntryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'items' => 'required|array|min:2',
            'items.*.chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.type' => 'required|in:debit,credit',
            'items.*.amount' => 'required|numeric|min:0.01',
        ]);

        // Validar equilíbrio (D = C)
        $totalDebit = 0;
        $totalCredit = 0;
        foreach ($request->items as $item) {
            if ($item['type'] === 'debit') $totalDebit += $item['amount'];
            else $totalCredit += $item['amount'];
        }

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return response()->json([
                'success' => false,
                'message' => 'O lançamento está desequilibrado. Total Débitos (R$ ' . number_format($totalDebit, 2) . ') != Total Créditos (R$ ' . number_format($totalCredit, 2) . ').'
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            $journalEntry = JournalEntry::create([
                'date' => $request->date,
                'description' => $request->description,
                'reference' => 'MAN-' . strtoupper(uniqid()),
            ]);

            foreach ($request->items as $item) {
                AccountingAudit::create([
                    'journal_entry_id' => $journalEntry->id,
                    'chart_of_account_id' => $item['chart_of_account_id'],
                    'type' => $item['type'],
                    'amount' => $item['amount'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lançamento contábil realizado com sucesso!',
                'redirect' => route('admin.accounting.journal.index')
            ]);
        });
    }
}

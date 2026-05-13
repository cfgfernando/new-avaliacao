<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Transaction;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\FinancialAccount;

class JournalEntryController extends Controller
{
    public function index()
    {
        $entries = Transaction::with(['financialAccount', 'chartOfAccount'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.finance.journal.index', compact('entries'));
    }
}

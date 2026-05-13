<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\FinancialAccount;
use App\Models\Finance\CostCenter;
use App\Models\Finance\Closure;
use App\Models\Finance\Supplier;
use App\Models\Finance\Bank;
use App\Models\HierarchyNode;
use App\Models\User;

class FinanceSettingController extends Controller
{
    public function index()
    {
        // Fetch data for the various settings tabs with pagination
        $units = HierarchyNode::paginate(10, ['*'], 'units_page');
        $chartOfAccounts = ChartOfAccount::orderBy('code')->paginate(10, ['*'], 'coa_page');
        $financialAccounts = FinancialAccount::paginate(10, ['*'], 'accounts_page');
        $costCenters = CostCenter::orderBy('name')->paginate(10, ['*'], 'cc_page');
        $suppliers = Supplier::orderBy('name')->paginate(10, ['*'], 'suppliers_page');
        $banks = Bank::orderBy('name')->paginate(10, ['*'], 'banks_page');
        $closures = Closure::orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(5, ['*'], 'closures_page');
        
        return view('admin.finance.settings.index', compact(
            'units', 
            'chartOfAccounts', 
            'financialAccounts', 
            'costCenters',
            'suppliers',
            'banks',
            'closures'
        ));
    }
}

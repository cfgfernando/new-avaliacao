<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finance\FinancialBatch;
use Carbon\Carbon;

class FinancialBatchSeeder extends Seeder
{
    public function run(): void
    {
        FinancialBatch::create([
            'code' => 'MAL-' . date('Y') . '-001',
            'status' => 'pending',
            'declared_amount' => 1500.50,
            'opened_at' => now(),
        ]);

        FinancialBatch::create([
            'code' => 'MAL-' . date('Y') . '-002',
            'status' => 'confirmed',
            'declared_amount' => 2750.00,
            'confirmed_amount' => 2750.00,
            'opened_at' => now()->subDays(2),
            'closed_at' => now()->subDays(1),
        ]);

        FinancialBatch::create([
            'code' => 'MAL-' . date('Y') . '-003',
            'status' => 'divergent',
            'declared_amount' => 5000.00,
            'confirmed_amount' => 4950.00,
            'opened_at' => now()->subDays(1),
        ]);
    }
}

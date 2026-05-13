<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['code' => '001', 'name' => 'Banco do Brasil S.A.', 'ispb' => '00000000'],
            ['code' => '033', 'name' => 'Banco Santander (Brasil) S.A.', 'ispb' => '90400888'],
            ['code' => '104', 'name' => 'Caixa Econômica Federal', 'ispb' => '00360305'],
            ['code' => '237', 'name' => 'Banco Bradesco S.A.', 'ispb' => '60746948'],
            ['code' => '341', 'name' => 'Itaú Unibanco S.A.', 'ispb' => '60701190'],
            ['code' => '077', 'name' => 'Banco Inter S.A.', 'ispb' => '00416968'],
            ['code' => '260', 'name' => 'Nu Pagamentos S.A. (Nubank)', 'ispb' => '18236120'],
            ['code' => '422', 'name' => 'Banco Safra S.A.', 'ispb' => '03012230'],
            ['code' => '633', 'name' => 'Banco Rendimento S.A.', 'ispb' => '31281398'],
            ['code' => '748', 'name' => 'Banco Cooperativo Sicredi S.A.', 'ispb' => '01181521'],
            ['code' => '756', 'name' => 'Banco Cooperativo do Brasil S.A. (Sicoob)', 'ispb' => '02038232'],
            ['code' => '212', 'name' => 'Banco Original S.A.', 'ispb' => '92894922'],
            ['code' => '336', 'name' => 'Banco C6 S.A.', 'ispb' => '31872495'],
            ['code' => '041', 'name' => 'Banco do Estado do Rio Grande do Sul S.A. (Banrisul)', 'ispb' => '92702067'],
            ['code' => '004', 'name' => 'Banco do Nordeste do Brasil S.A.', 'ispb' => '07237373'],
        ];

        foreach ($banks as $bank) {
            DB::table('banks')->updateOrInsert(
                ['code' => $bank['code']],
                array_merge($bank, [
                    'country' => 'Brasil',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}

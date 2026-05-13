<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Remover a categoria antiga "Financeiro"
DB::table('menu_categories')->where('name', 'Financeiro')->delete();

// 2. Remover itens de menu que possam estar sobrando com títulos antigos
DB::table('menus')->where('title', 'Financeiro')->delete();
DB::table('menus')->where('title', 'Módulo Financeiro')->where('url', '!=', 'admin/finance')->delete();

echo "Limpeza de menus concluída com sucesso!\n";

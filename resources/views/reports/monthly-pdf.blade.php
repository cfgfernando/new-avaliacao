<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Relatório Mensal de Malotes - MDA Church</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#1C2434",
                        "accent": "#FF9C00",
                        "outline": "#E2E8F0",
                        "surface": "#FFFFFF",
                        "surface-dim": "#F8FAFC",
                        "on-surface": "#1C2434",
                        "on-surface-variant": "#64748B",
                        "semantic-blue": "#3B82F6",
                        "semantic-green": "#10B981",
                        "semantic-purple": "#8B5CF6"
                    },
                    "fontFamily": {
                        "sans": ["Libre Franklin", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        /* Configurações de página físicas para A4 Retrato */
        @page {
            size: A4 portrait;
            margin: 15mm 15mm !important;
        }
        
        /* Regras exclusivas para visualização na Tela (Preview do ERP) */
        @media screen {
            body {
                background-color: #f1f5f9 !important;
                padding: 2rem !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
            }
            .a4-page {
                width: 210mm !important;
                height: 297mm !important;
                min-height: 297mm !important;
                max-height: 297mm !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
                border: 1px solid #e2e8f0 !important;
                background-color: white !important;
                margin: auto !important;
                flex-shrink: 0 !important;
                padding: 15mm !important;
                box-sizing: border-box !important;
                position: relative !important;
            }
        }

        /* Regras exclusivas para Impressão Física / Geração de PDF */
        @media print {
            .no-print { display: none !important; }
            html, body {
                background-color: white !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: 100% !important;
                overflow: hidden !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .a4-page {
                width: 100% !important;
                height: 100% !important;
                min-height: 100% !important;
                max-height: 100% !important;
                margin: 0 !important;
                padding: 0 !important; /* Margem controlada pela @page */
                border: none !important;
                box-shadow: none !important;
                box-sizing: border-box !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                page-break-after: avoid !important;
                page-break-before: avoid !important;
                page-break-inside: avoid !important;
                flex-shrink: 0 !important;
                position: relative !important;
            }
            .inner-container {
                height: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                box-sizing: border-box !important;
                position: relative !important;
            }
        }
        
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Libre Franklin', sans-serif; }
        .inner-container {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Estilo da Tabela com Bordas Seguras e Legibilidade de Impressão */
        table.mda-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.mda-table th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            text-transform: uppercase;
            font-size: 9px;
            font-weight: 800;
            border: 1px solid #cbd5e1 !important;
            border-bottom: 2px solid #94a3b8 !important;
            padding: 8px 10px;
        }
        table.mda-table td {
            border: 1px solid #cbd5e1 !important;
            padding: 8px 10px;
            vertical-align: middle;
        }
        table.mda-table tr {
            page-break-inside: avoid !important;
        }
        table.mda-table tr.total-row td {
            border-top: 2px solid #1c2434 !important;
            border-bottom: 2px solid #1c2434 !important;
            background-color: rgba(28, 36, 52, 0.05) !important;
            font-weight: bold;
        }
        
        .signatures-container {
            page-break-inside: avoid !important;
        }
    </style>
</head>
<body class="text-on-surface bg-gray-100 flex flex-col items-center">

<!-- UI Controls (Non-Printable) -->
<div class="no-print w-full max-w-[210mm] flex justify-between mb-6 mt-4 items-center">
    <div class="flex items-center gap-3">
        <a class="flex items-center justify-center p-2.5 rounded-full bg-white text-primary border border-outline hover:bg-slate-50 transition-all shadow-sm" href="{{ route('reports.index') }}">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <h1 class="text-primary font-black text-lg uppercase tracking-tight">Visualização Consolidada</h1>
    </div>
    <div class="flex gap-2">
        <button class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-opacity-95 transition-all shadow-md shadow-primary/20" onclick="window.print()">
            <span class="material-symbols-outlined text-[16px]">print</span>
            <span>Imprimir Relatório</span>
        </button>
    </div>
</div>

<!-- A4 Document Container -->
<div class="a4-page w-[210mm] bg-white shadow-2xl overflow-hidden flex flex-col p-[15mm] relative box-border m-auto shrink-0">
    <!-- Inner content container that stretches dynamically -->
    <div class="inner-container">
        
        <div>
            <!-- Header / Branding -->
            <div class="flex justify-between items-start border-b-2 border-primary pb-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary flex items-center justify-center rounded-xl text-accent">
                        <span class="material-symbols-outlined text-[32px] fill-icon">church</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-on-surface-variant uppercase tracking-[0.2em]">MDA Church</p>
                        <h1 class="text-lg font-black text-primary uppercase tracking-tight leading-tight">Relatório Mensal de Malotes</h1>
                        <p class="text-[9px] text-on-surface-variant font-bold uppercase mt-0.5 tracking-wider">Fechamento Consolidado Geral</p>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end gap-1">
                    <div class="inline-block bg-accent/10 border border-accent/20 text-accent px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-0.5">
                        Consolidado Mensal
                    </div>
                    <p class="text-[10px] text-on-surface-variant font-bold tracking-widest uppercase">
                        DOC ID: #MDA-{{ now()->format('Y') }}-{{ str_pad($reportsCount, 3, '0', STR_PAD_LEFT) }}
                    </p>
                    @php
                        $monthYearLabel = '';
                        if (request('date_from') && request('date_to')) {
                            $from = \Carbon\Carbon::parse(request('date_from'));
                            $to = \Carbon\Carbon::parse(request('date_to'));
                            if ($from->format('Y-m') === $to->format('Y-m')) {
                                $monthYearLabel = $from->translatedFormat('F \/ Y');
                            } else {
                                $monthYearLabel = $from->format('d/m/Y') . ' - ' . $to->format('d/m/Y');
                            }
                        } elseif (request('date_from')) {
                            $monthYearLabel = 'A partir de ' . \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y');
                        } else {
                            $monthYearLabel = now()->translatedFormat('F \/ Y');
                        }
                        $monthYearLabel = ucwords($monthYearLabel);
                    @endphp
                    <p class="text-sm font-black text-primary uppercase tracking-tight">{{ $monthYearLabel }}</p>
                </div>
            </div>

            <!-- General Info Grid -->
            <div class="grid grid-cols-4 gap-4 mb-6 pb-4 border-b border-outline">
                <div>
                    <p class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest mb-0.5">Escopo do Relatório</p>
                    <p class="text-[11px] font-bold text-primary truncate">
                        {{ $selectedCell ? $selectedCell->name : 'Todas as Células (Rede)' }}
                    </p>
                </div>
                <div>
                    <p class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest mb-0.5">Responsável / Célula</p>
                    <p class="text-[11px] font-bold text-primary truncate">
                        {{ $selectedCell?->leader?->name ?? 'Liderança Coletiva' }}
                    </p>
                </div>
                <div>
                    <p class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest mb-0.5">Setor / Área</p>
                    @php
                        $sectorAreaLabel = 'Todos';
                        if ($selectedCell) {
                            $sector = $selectedCell->node?->name ?? 'N/A';
                            $area = $selectedCell->node?->parent?->name ?? 'N/A';
                            $sectorAreaLabel = "$sector \/ $area";
                        } elseif (request('sector_id')) {
                            $sec = \App\Models\HierarchyNode::find(request('sector_id'));
                            $sectorAreaLabel = ($sec?->name ?? 'N/A') . ' \/ ' . ($sec?->parent?->name ?? 'Todos');
                        } elseif (request('area_id')) {
                            $ar = \App\Models\HierarchyNode::find(request('area_id'));
                            $sectorAreaLabel = 'Todos \/ ' . ($ar?->name ?? 'N/A');
                        }
                    @endphp
                    <p class="text-[11px] font-bold text-primary truncate">{{ $sectorAreaLabel }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest mb-0.5">Emissão do Relatório</p>
                    <p class="text-[11px] font-bold text-primary">{{ now()->format('d/m/Y \à\s H:i') }}</p>
                </div>
            </div>

            <!-- Executive Summary Cards -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                {{-- Card 1: Frequência --}}
                <div class="p-4 border border-outline rounded-xl bg-surface flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest">Participantes & Freq.</span>
                            <span class="material-symbols-outlined text-[18px] text-semantic-blue">groups</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-black text-primary tracking-tighter leading-none">{{ $totalPresence }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">Média: {{ $averagePresence }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-outline/50 flex justify-between text-[9px] font-bold text-on-surface-variant uppercase tracking-wider">
                        <span>Membros: {{ $presentMembers }}</span>
                        <span>Vis.: {{ $visitors }}</span>
                    </div>
                </div>

                {{-- Card 2: Impacto Ministerial --}}
                <div class="p-4 border border-outline rounded-xl bg-surface flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest">Impacto Ministerial</span>
                            <span class="material-symbols-outlined text-[18px] text-semantic-purple">volunteer_activism</span>
                        </div>
                        <div class="space-y-1">
                            <div class="flex justify-between text-[10px]">
                                <span class="text-on-surface-variant font-bold">Novas Decisões:</span>
                                <span class="font-extrabold text-accent">{{ $conversions }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span class="text-on-surface-variant font-bold">Casas de Paz / MDAs:</span>
                                <span class="font-extrabold text-accent">{{ $houseOfPeace }} / {{ $mdasDone }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span class="text-on-surface-variant font-bold">Quilo do Amor:</span>
                                <span class="font-extrabold text-accent">{{ number_format($kgOfLove, 1) }} Kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Consolidado Financeiro --}}
                <div class="p-4 border border-outline rounded-xl bg-surface flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest">Consolidado Financeiro</span>
                            <span class="material-symbols-outlined text-[18px] text-accent">payments</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-emerald-600 tracking-tighter leading-none">R$ {{ number_format($totalOffer, 2, ',', '.') }}</span>
                            <span class="text-[8px] text-on-surface-variant font-bold uppercase mt-1">Total de {{ $reportsCount }} malotes</span>
                        </div>
                    </div>
                    @php
                        $pixPercent = $totalOffer > 0 ? round(($offerPix / $totalOffer) * 100) : 0;
                        $cashPercent = $totalOffer > 0 ? round(($offerCash / $totalOffer) * 100) : 0;
                    @endphp
                    <div class="mt-4 pt-3 border-t border-outline/50 flex justify-between text-[9px] font-bold text-on-surface-variant uppercase tracking-wider">
                        <span class="text-sky-500">PIX: {{ $pixPercent }}%</span>
                        <span>Dinheiro: {{ $cashPercent }}%</span>
                    </div>
                </div>
            </div>

            <!-- Weekly Breakdown Table -->
            <div class="space-y-3 mb-6">
                <h3 class="text-[9px] font-black text-primary uppercase tracking-widest mb-1">Detalhamento dos Lançamentos Coletados</h3>
                <div class="bg-surface shadow-sm rounded-xl overflow-hidden">
                    <table class="mda-table">
                        <thead>
                            <tr>
                                <th class="text-left">Período / Data</th>
                                @if(!$selectedCell)
                                    <th class="text-left">Célula / Local</th>
                                @endif
                                <th class="text-left">Tema da Palavra</th>
                                <th class="text-center">Freq.</th>
                                <th class="text-right">Oferta Bruta</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $index => $r)
                                <tr class="{{ $index % 2 === 1 ? 'bg-surface-dim/40' : '' }}">
                                    <td class="text-[11px] font-bold text-primary">
                                        {{ $r->meeting_date?->format('d/m/Y') }}
                                        <p class="text-[8px] text-on-surface-variant font-bold uppercase mt-0.5">Semana {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p>
                                    </td>
                                    @if(!$selectedCell)
                                        <td class="text-[11px] font-extrabold text-primary">
                                            {{ $r->cell?->name }}
                                            <p class="text-[8px] text-on-surface-variant font-semibold mt-0.5 truncate max-w-[130px]">{{ $r->cell?->leader?->name ?? 'N/I' }}</p>
                                        </td>
                                    @endif
                                    <td class="text-[11px] text-on-surface-variant italic truncate max-w-[160px]">
                                        "{{ $r->word_theme ?? 'Tema não informado' }}"
                                    </td>
                                    <td class="text-center text-[11px] font-bold text-primary">
                                        {{ $r->total_presence }}
                                        <p class="text-[8px] text-on-surface-variant font-bold mt-0.5">{{ $r->present_members }}M | {{ $r->visitors }}V | {{ $r->children }}C</p>
                                    </td>
                                    <td class="text-right text-[11px] font-extrabold text-emerald-600">
                                        R$ {{ number_format($r->total_offer, 2, ',', '.') }}
                                        <p class="text-[8px] text-on-surface-variant font-medium mt-0.5">PIX {{ number_format($r->offer_pix, 2, ',', '.') }} | Din {{ number_format($r->offer_cash, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badgeStyle = match($r->status) {
                                                'Conciliated' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'Submitted'   => 'bg-amber-50 text-amber-600 border-amber-100',
                                                default       => 'bg-slate-100 text-slate-600 border-slate-200',
                                            };
                                            $badgeText = match($r->status) {
                                                'Conciliated' => 'LIDO',
                                                'Submitted'   => 'ENVIADO',
                                                default       => 'RASCUNHO',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 text-[8px] font-black rounded-full border {{ $badgeStyle }}">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $selectedCell ? 5 : 6 }}" class="p-8 text-center text-on-surface-variant italic text-[11px]">
                                        Nenhum malote encontrado no período selecionado.
                                    </td>
                                </tr>
                            @endforelse

                            @if($reports->isNotEmpty())
                                <tr class="total-row font-bold">
                                    <td class="text-[10px] font-black uppercase text-primary">Totais Consolidados</td>
                                    @if(!$selectedCell)
                                        <td></td>
                                    @endif
                                    <td></td>
                                    <td class="text-center text-[11px] font-black text-primary">
                                        {{ $totalPresence }}
                                        <p class="text-[8px] text-on-surface-variant font-bold mt-0.5">Total Geral</p>
                                    </td>
                                    <td class="text-right text-[11px] font-black text-emerald-600">
                                        R$ {{ number_format($totalOffer, 2, ',', '.') }}
                                        <p class="text-[8px] text-on-surface-variant font-bold mt-0.5">PIX {{ number_format($offerPix, 2, ',', '.') }} | Din {{ number_format($offerCash, 2, ',', '.') }}</p>
                                    </td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Footer / Signatures -->
        <div class="absolute bottom-[22mm] left-[15mm] right-[15mm] signatures-container">
            <div class="grid grid-cols-2 gap-12">
                <div class="text-center">
                    <div class="border-b border-slate-300 mb-2"></div>
                    <p class="text-[9px] text-on-surface font-black uppercase tracking-widest">
                        Líder de Célula / Supervisor: {{ $selectedCell?->leader?->name ?? 'Liderança Responsável' }}
                    </p>
                </div>
                <div class="text-center">
                    <div class="border-b border-slate-300 mb-2"></div>
                    <p class="text-[9px] text-on-surface font-black uppercase tracking-widest">Tesouraria Geral / Auditoria</p>
                </div>
            </div>
        </div>

        <!-- Footer Meta Text (Stamp) -->
        <div class="absolute bottom-[6mm] left-[15mm] right-[15mm] text-center">
            <p class="text-[7.5px] text-on-surface-variant uppercase tracking-[0.25em] font-bold">
                Documento Consolidado Emitido Digitalmente via ERP MDA Church • Autenticação de Auditoria Interna
            </p>
        </div>
        
    </div>
</div>

</body>
</html>

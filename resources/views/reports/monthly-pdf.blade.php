<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <title>Consolidado Mensal de Malotes & Célula</title>
    <style>
        @page {
            margin: 1.2cm;
        }
        body {
            background-color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #1c2434;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .logo-box {
            width: 50px;
            height: 50px;
            background-color: #1c2434;
            border-radius: 8px;
            text-align: center;
            vertical-align: middle;
            color: #f59e0b;
            font-weight: 700;
            font-size: 20pt;
            line-height: 50px;
        }
        .title {
            font-size: 16pt;
            font-weight: 700;
            color: #1c2434;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .subtitle {
            font-size: 8.5pt;
            color: #8a99af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 2px 0 0 0;
            font-weight: 600;
        }
        .accent-bar {
            width: 100%;
            height: 4px;
            background-color: #f59e0b;
            margin-bottom: 15px;
        }
        
        /* Stats Grid */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .stats-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f8fafc;
            padding: 10px 15px;
            text-align: center;
        }
        .stats-label {
            font-size: 7.5pt;
            font-weight: 600;
            color: #8a99af;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .stats-val {
            font-size: 14pt;
            font-weight: 700;
            color: #1c2434;
        }
        .stats-val-accent {
            color: #f59e0b;
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #1c2434;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 7.5pt;
            padding: 8px 10px;
            border-bottom: 2px solid #cbd5e1;
            text-align: left;
        }
        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8.5pt;
            color: #334155;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .status-badge {
            font-size: 7.5pt;
            font-weight: 600;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 9999px;
            display: inline-block;
        }
        .status-conciliated {
            color: #065f46;
            background-color: #d1fae5;
        }
        .status-submitted {
            color: #1e3a8a;
            background-color: #dbeafe;
        }
        .status-draft {
            color: #9a3412;
            background-color: #ffedd5;
        }
        
        /* Two Column Layout */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .layout-table td {
            vertical-align: top;
        }
        
        .panel {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            background-color: #ffffff;
        }
        .panel-title {
            font-size: 9pt;
            font-weight: 700;
            color: #1c2434;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .panel-row {
            width: 100%;
            border-bottom: 1px solid #f1f5f9;
            padding: 5px 0;
            font-size: 8.5pt;
        }
        
        /* Footer signatures */
        .footer-signatures {
            margin-top: 40px;
            width: 100%;
            border-collapse: collapse;
        }
        .signature-line {
            border-top: 1px solid #1c2434;
            text-align: center;
            font-size: 8pt;
            padding-top: 5px;
            color: #1c2434;
        }
        .footer-text {
            text-align: center;
            font-size: 7.5pt;
            color: #8a99af;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="65">
                <div class="logo-box">M</div>
            </td>
            <td valign="middle">
                <h1 class="title">Gestão MDA • Consolidação de Malotes</h1>
                <p class="subtitle">
                    @if($selectedCell)
                        Célula: {{ $selectedCell->name }} • Líder: {{ $selectedCell->leader?->name ?? 'Não Informado' }}
                    @else
                        Relatório Consolidado de Malotes — Todas as Células da Rede
                    @endif
                </p>
            </td>
            <td align="right" valign="middle">
                <p style="font-size: 8pt; color: #8a99af; font-weight: 600; margin: 0; text-transform: uppercase;">
                    Gerado em: {{ $generated_at }}
                </p>
            </td>
        </tr>
    </table>
    
    <!-- Accent Line -->
    <div class="accent-bar"></div>

    <!-- Stats Cards Grid -->
    <table class="stats-table">
        <tr>
            <td width="24%" style="padding-right: 1%;">
                <div class="stats-card">
                    <div class="stats-label">Total Arrecadado</div>
                    <div class="stats-val stats-val-accent">R$ {{ number_format($totalOffer, 2, ',', '.') }}</div>
                </div>
            </td>
            <td width="24%" style="padding-right: 1%;">
                <div class="stats-card">
                    <div class="stats-label">Total PIX / Dinheiro</div>
                    <div class="stats-val" style="font-size: 11pt; padding-top: 3px;">
                        PIX: R$ {{ number_format($offerPix, 2, ',', '.') }}<br/>
                        DIN: R$ {{ number_format($offerCash, 2, ',', '.') }}
                    </div>
                </div>
            </td>
            <td width="18%" style="padding-right: 1%;">
                <div class="stats-card">
                    <div class="stats-label">Total de Malotes</div>
                    <div class="stats-val">{{ $reportsCount }}</div>
                </div>
            </td>
            <td width="18%" style="padding-right: 1%;">
                <div class="stats-card">
                    <div class="stats-label">Frequência Total</div>
                    <div class="stats-val" style="color: #3b82f6;">{{ $totalPresence }} <span style="font-size: 7.5pt; color: #8a99af; font-weight: 400;">Part.</span></div>
                </div>
            </td>
            <td width="16%">
                <div class="stats-card">
                    <div class="stats-label">Presença Média</div>
                    <div class="stats-val" style="color: #10b981;">{{ $averagePresence }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Main List of Malotes/Reports -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">Data</th>
                @if(!$selectedCell)
                    <th width="18%">Célula</th>
                @endif
                <th width="20%">Tema da Palavra</th>
                <th width="10%">Membros</th>
                <th width="10%">Visitantes</th>
                <th width="10%">Frequência</th>
                <th width="10%">Oferta PIX</th>
                <th width="10%">Oferta DIN</th>
                <th width="12%">Total Geral</th>
                <th width="10%">Situação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $r)
                <tr>
                    <td>{{ $r->meeting_date?->format('d/m/Y') }}</td>
                    @if(!$selectedCell)
                        <td><strong>{{ $r->cell?->name }}</strong></td>
                    @endif
                    <td style="font-style: italic;">"{{ $r->word_theme ?? 'Não informado' }}"</td>
                    <td>{{ $r->present_members }}</td>
                    <td>{{ $r->visitors }}</td>
                    <td><strong>{{ $r->total_presence }}</strong></td>
                    <td>R$ {{ number_format($r->offer_pix, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($r->offer_cash, 2, ',', '.') }}</td>
                    <td style="color: #f59e0b; font-weight: bold;">R$ {{ number_format($r->total_offer, 2, ',', '.') }}</td>
                    <td>
                        @if($r->status === 'Conciliated')
                            <span class="status-badge status-conciliated">Conciliado</span>
                        @elseif($r->status === 'Submitted')
                            <span class="status-badge status-submitted">Submetido</span>
                        @else
                            <span class="status-badge status-draft">Rascunho</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $selectedCell ? 9 : 10 }}" align="center" style="color: #8a99af; font-style: italic; padding: 20px 0;">
                        Nenhum malote/relatório encontrado para os critérios selecionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary panels (Miniterial Impact & Checklist) -->
    <table class="layout-table">
        <tr>
            <td width="48%" style="padding-right: 4%;">
                <div class="panel">
                    <div class="panel-title">Resumo do Impacto Ministerial</div>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="panel-row">
                            <td>Novas Decisões (Conversões):</td>
                            <td align="right" style="font-weight: bold; color: #10b981;">{{ $conversions }}</td>
                        </tr>
                        <tr class="panel-row">
                            <td>Reconciliações de Vidas:</td>
                            <td align="right" style="font-weight: bold;">{{ $reconciliations }}</td>
                        </tr>
                        <tr class="panel-row">
                            <td>Casas de Paz Abertas:</td>
                            <td align="right" style="font-weight: bold;">{{ $houseOfPeace }}</td>
                        </tr>
                        <tr class="panel-row">
                            <td>Discipulados Realizados (MDAs):</td>
                            <td align="right" style="font-weight: bold;">{{ $mdasDone }}</td>
                        </tr>
                        <tr class="panel-row" style="border-bottom: 0;">
                            <td>Quilo do Amor:</td>
                            <td align="right" style="font-weight: bold;">{{ number_format($kgOfLove, 1) }} Kg</td>
                        </tr>
                    </table>
                </div>
            </td>
            
            <td width="48%">
                <div class="panel">
                    <div class="panel-title">Auditoria e Conciliação</div>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="panel-row">
                            <td>Malotes Conciliados:</td>
                            <td align="right" style="font-weight: bold; color: #065f46;">
                                {{ $reports->where('status', 'Conciliated')->count() }} / {{ $reportsCount }}
                            </td>
                        </tr>
                        <tr class="panel-row">
                            <td>Malotes Submetidos Pendentes:</td>
                            <td align="right" style="font-weight: bold; color: #1e3a8a;">
                                {{ $reports->where('status', 'Submitted')->count() }}
                            </td>
                        </tr>
                        <tr class="panel-row">
                            <td>Malotes em Rascunho (Não Submetidos):</td>
                            <td align="right" style="font-weight: bold; color: #9a3412;">
                                {{ $reports->where('status', 'Draft')->count() }}
                            </td>
                        </tr>
                        <tr class="panel-row" style="border-bottom: 0;">
                            <td>Status Geral do Fechamento:</td>
                            <td align="right" style="font-weight: bold;">
                                @if($reportsCount > 0 && $reports->where('status', 'Conciliated')->count() === $reportsCount)
                                    FECHADO / CONCILIADO
                                @else
                                    EM ABERTO
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer Signatures -->
    <table class="footer-signatures" cellpadding="0" cellspacing="0">
        <tr>
            <td width="30%">
                <div class="signature-line">
                    Líder da Célula / Responsável
                </div>
            </td>
            <td width="5%"></td>
            <td width="30%">
                <div class="signature-line">
                    Supervisor / Discipulador
                </div>
            </td>
            <td width="5%"></td>
            <td width="30%">
                <div class="signature-line">
                    Tesouraria Geral / Auditoria
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-text">
        Relatório Consolidado de Malotes Semanal • Sistema ERP MDA Church • Em conformidade com as normas financeiras do conselho pastoral
    </div>

</body>
</html>

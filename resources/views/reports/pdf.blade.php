<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <title>Relatório Semanal de Célula & Malote</title>
    <style>
        @page {
            margin: 1.2cm;
        }
        body {
            background-color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #171c1f;
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
            width: 55px;
            height: 55px;
            background-color: #1c2434;
            border-radius: 8px;
            text-align: center;
            vertical-align: middle;
            color: #F59E0B;
            font-weight: 700;
            font-size: 22pt;
            line-height: 55px;
        }
        .title {
            font-size: 18pt;
            font-weight: 700;
            color: #060e1e;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .subtitle {
            font-size: 9.5pt;
            color: #45474c;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 2px 0 0 0;
            font-weight: 600;
        }
        .status-badge {
            font-size: 8pt;
            font-weight: 600;
            color: #45474c;
            text-transform: uppercase;
            padding: 4px 12px;
            background-color: rgba(220, 226, 247, 0.3);
            border: 1px solid #c6c6cd;
            border-radius: 9999px;
            display: inline-block;
        }
        .status-badge-conciliated {
            color: #065f46;
            background-color: #d1fae5;
            border-color: #a7f3d0;
        }
        .status-badge-draft {
            color: #9a3412;
            background-color: #ffedd5;
            border-color: #fed7aa;
        }
        .accent-bar {
            width: 100%;
            height: 4px;
            background-color: #F59E0B;
            margin-bottom: 20px;
        }
        
        .main-layout {
            width: 100%;
            border-collapse: collapse;
        }
        .main-layout > tr > td {
            vertical-align: top;
        }
        
        .card {
            border: 1px solid #c6c6cd;
            border-radius: 8px;
            background-color: #ffffff;
            padding: 18px;
            margin-bottom: 18px;
        }
        
        .card-title {
            font-size: 10pt;
            font-weight: 700;
            color: #060e1e;
            text-transform: uppercase;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(198, 198, 205, 0.3);
        }
        
        .label {
            font-size: 7.5pt;
            font-weight: 600;
            color: #45474c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .value-headline {
            font-size: 13pt;
            font-weight: 600;
            color: #060e1e;
            margin: 0;
        }
        .value-text {
            font-size: 10pt;
            color: #060e1e;
            margin: 0;
        }
        
        .freq-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .freq-box {
            background-color: #eaeef2;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }
        .freq-num {
            font-size: 18pt;
            font-weight: 700;
            line-height: 1.1;
        }
        
        .row-item {
            width: 100%;
            border-bottom: 1px solid rgba(198, 198, 205, 0.3);
            padding: 8px 0;
        }
        .row-item-last {
            width: 100%;
            padding: 8px 0;
        }
        
        .finance-total-box {
            background-color: #f6fafe;
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            margin-top: 15px;
        }
        
        .footer-block {
            position: absolute;
            bottom: -15px;
            left: 0;
            right: 0;
            width: 100%;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .signature-line {
            border-top: 1px solid #060e1e;
            text-align: center;
            font-size: 9pt;
            color: #171c1f;
            padding-top: 5px;
        }
        .footer-text {
            text-align: center;
            font-size: 7.5pt;
            color: #45474c;
            border-top: 1px solid rgba(198, 198, 205, 0.3);
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="70">
                <div class="logo-box">M</div>
            </td>
            <td valign="middle">
                <h1 class="title">Gestão MDA</h1>
                <p class="subtitle">Relatório Semanal de Célula & Malote</p>
            </td>
            <td align="right" valign="middle">
                @if($report->status === 'Conciliated')
                    <span class="status-badge status-badge-conciliated">Conciliado</span>
                @elseif($report->status === 'Submitted')
                    <span class="status-badge">Submetido</span>
                @else
                    <span class="status-badge status-badge-draft">Rascunho</span>
                @endif
            </td>
        </tr>
    </table>
    
    <!-- Accent Line -->
    <div class="accent-bar"></div>

    <!-- Summary Content Grid (2 Columns) -->
    <table class="main-layout" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Left Column (58%) -->
            <td width="57%">
                
                <!-- Informações Gerais -->
                <div class="card">
                    <div class="card-title">
                        <svg style="width: 14px; height: 14px; fill: #060e1e; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                        Informações Gerais
                    </div>
                    
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 12px;">
                        <tr>
                            <td width="50%">
                                <p class="label">Célula</p>
                                <p class="value-headline" style="color: #060e1e;">{{ $report->cell->name }}</p>
                            </td>
                            <td width="50%">
                                <p class="label">Data da Reunião</p>
                                <p class="value-headline" style="color: #060e1e;">{{ $report->meeting_date?->format('d/m/Y') }}</p>
                            </td>
                        </tr>
                    </table>
                    
                    <div style="margin-bottom: 12px;">
                        <p class="label">Tema da Palavra</p>
                        <p class="value-text" style="color: #F59E0B; font-style: italic; font-weight: 600;">"{{ $report->word_theme ?? 'Não informado' }}"</p>
                    </div>
                    
                    <div>
                        <p class="label">Local da Reunião</p>
                        <p class="value-text">{{ $report->meeting_location ?? 'Não informado' }}</p>
                    </div>
                </div>

                <!-- Frequência da Reunião -->
                <div class="card">
                    <div class="card-title">
                        <svg style="width: 14px; height: 14px; fill: #060e1e; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 1.34 5 3s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        Frequência da Reunião
                    </div>
                    
                    <table class="freq-grid" cellpadding="0" cellspacing="4">
                        <tr>
                            <td width="33%">
                                <div class="freq-box">
                                    <p class="freq-num" style="color: #3B82F6;">{{ $report->present_members }}</p>
                                    <p class="label" style="font-size: 6.5pt; margin-top: 2px;">Membros</p>
                                </div>
                            </td>
                            <td width="33%">
                                <div class="freq-box">
                                    <p class="freq-num" style="color: #F59E0B;">{{ $report->visitors }}</p>
                                    <p class="label" style="font-size: 6.5pt; margin-top: 2px;">Visitantes</p>
                                </div>
                            </td>
                            <td width="33%">
                                <div class="freq-box">
                                    <p class="freq-num" style="color: #060e1e;">{{ $report->total_presence }}</p>
                                    <p class="label" style="font-size: 6.5pt; margin-top: 2px;">Total Geral</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                    <table width="100%" cellpadding="0" cellspacing="0" class="row-item">
                        <tr>
                            <td class="value-text">Crianças presentes:</td>
                            <td align="right" class="value-headline" style="font-size: 11pt;">{{ $report->children ?? 0 }}</td>
                        </tr>
                    </table>
                    
                    <table width="100%" cellpadding="0" cellspacing="0" class="row-item-last">
                        <tr>
                            <td class="value-text">Visitantes de outras células:</td>
                            <td align="right" class="value-headline" style="font-size: 11pt;">{{ $report->other_cell_visitors ?? 0 }}</td>
                        </tr>
                    </table>
                </div>

            </td>
            
            <!-- Spacer Column (3%) -->
            <td width="3%"></td>
            
            <!-- Right Column (40%) -->
            <td width="40%">
                
                <!-- Financeiro e Ofertas -->
                <div class="card" style="border: 2px solid #F59E0B;">
                    <div class="card-title" style="color: #F59E0B; border-bottom: 1px solid rgba(245, 158, 11, 0.3);">
                        <svg style="width: 14px; height: 14px; fill: #F59E0B; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24">
                            <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2-.9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                        </svg>
                        Financeiro e Ofertas
                    </div>
                    
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;">
                        <tr class="row-item">
                            <td class="value-text">Ofertas via PIX:</td>
                            <td align="right" class="value-headline" style="font-size: 11pt;">R$ {{ number_format($report->offer_pix ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="row-item-last">
                            <td class="value-text" style="padding-top: 8px;">Ofertas em Espécie:</td>
                            <td align="right" class="value-headline" style="font-size: 11pt; padding-top: 8px;">R$ {{ number_format($report->offer_cash ?? 0, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                    
                    <div class="finance-total-box">
                        <p class="label" style="color: #F59E0B; font-size: 7.5pt; margin-bottom: 2px;">Total do Malote Semanal</p>
                        <p class="value-headline" style="font-size: 18pt; color: #F59E0B; font-weight: 700;">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Impacto Ministerial -->
                <div class="card">
                    <div class="card-title">
                        <svg style="width: 14px; height: 14px; fill: #060e1e; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24">
                            <path d="M14.06 9.02L15.62 10.58L16.29 9.91L15.23 8.85L16.29 7.79L18.41 9.91L16.29 12.03L15.23 10.97L13 13.2L11 11.2L6 16.2L4.59 14.79L11 8.38L13 10.38L14.06 9.02ZM20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4H20ZM20 18H4V6H20V18Z"/>
                        </svg>
                        Impacto Ministerial
                    </div>
                    
                    <table width="100%" cellpadding="0" cellspacing="0" class="row-item">
                        <tr>
                            <td class="value-text" style="font-size: 8.5pt;">Novas Decisões (Conversões):</td>
                            <td align="right" class="value-headline" style="font-size: 10pt; color: #10B981;">{{ $report->conversions ?? 0 }}</td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" class="row-item">
                        <tr>
                            <td class="value-text" style="font-size: 8.5pt;">Reconciliações:</td>
                            <td align="right" class="value-headline" style="font-size: 10pt;">{{ $report->reconciliations ?? 0 }}</td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" class="row-item">
                        <tr>
                            <td class="value-text" style="font-size: 8.5pt;">Casas de Paz Abertas:</td>
                            <td align="right" class="value-headline" style="font-size: 10pt;">{{ $report->house_of_peace ?? 0 }}</td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" class="row-item">
                        <tr>
                            <td class="value-text" style="font-size: 8.5pt;">Discipulados Realizados (MDAs):</td>
                            <td align="right" class="value-headline" style="font-size: 10pt;">{{ $report->mdas_done ?? 0 }}</td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" class="row-item-last">
                        <tr>
                            <td class="value-text" style="font-size: 8.5pt;">Quilo do Amor:</td>
                            <td align="right" class="value-headline" style="font-size: 10pt;">{{ number_format($report->kg_of_love ?? 0, 1) }} Kg</td>
                        </tr>
                    </table>
                </div>

            </td>
        </tr>
    </table>

    <!-- Lista de Chamada de Membros -->
    <div class="card" style="margin-bottom: 90px;">
        <div class="card-title">
            <svg style="width: 14px; height: 14px; fill: #060e1e; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm0 4c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H6v-1.4c0-2 4-3.1 6-3.1s6 1.1 6 3.1V19z"/>
            </svg>
            Lista de Chamada
        </div>
        
        @if($presentMembers->count() > 0)
            @php
                $chunks = $presentMembers->chunk(2);
            @endphp
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($chunks as $chunk)
                    <tr>
                        @foreach($chunk as $member)
                            <td style="width: 50%; padding: 6px 0; border-bottom: 1px solid rgba(198, 198, 205, 0.3); font-size: 9.5pt; color: #171c1f; vertical-align: middle;">
                                <svg style="width: 13px; height: 13px; fill: #10B981; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                {{ $member->name }}
                            </td>
                        @endforeach
                        @if($chunk->count() == 1)
                            <td style="width: 50%; border-bottom: 1px solid rgba(198, 198, 205, 0.3);"></td>
                        @endif
                    </tr>
                @endforeach
                <!-- Empty lines for manually adding members/visitors if needed -->
                @for($i = 0; $i < 2; $i++)
                    <tr>
                        <td style="width: 50%; padding: 6px 0; border-bottom: 1px solid rgba(198, 198, 205, 0.3); height: 25px;"></td>
                        <td style="width: 50%; padding: 6px 0; border-bottom: 1px solid rgba(198, 198, 205, 0.3); height: 25px;"></td>
                    </tr>
                @endfor
            </table>
        @else
            <p style="font-size: 8.5pt; color: #45474c; font-style: italic; margin: 5px 0;">Nenhum membro presente registrado nesta reunião.</p>
        @endif

        @if(!empty($report->visitor_names) && count(array_filter($report->visitor_names)) > 0)
            <div class="label" style="margin-top: 15px; margin-bottom: 6px; font-weight: bold;">Visitantes Registrados</div>
            <div style="font-size: 9pt; color: #171c1f; padding-left: 6px; line-height: 1.4;">
                @foreach(array_filter($report->visitor_names) as $vName)
                    • {{ $vName }}<br/>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Bloco de Rodapé com Assinaturas e Informações do Sistema -->
    <div class="footer-block">
        <!-- Seção de Assinaturas -->
        <table class="signatures-table" cellpadding="0" cellspacing="0">
            <tr>
                <td width="46%">
                    <div class="signature-line" style="margin-top: 15px;">
                        <strong>Líder de Célula</strong><br/>
                        <span style="font-size: 7.5pt; color: #45474c;">{{ $report->submittedBy->name ?? 'Responsável' }}</span>
                    </div>
                </td>
                <td width="8%"></td>
                <td width="46%">
                    <div class="signature-line" style="margin-top: 15px;">
                        <strong>Tesouraria / Auditoria</strong><br/>
                        <span style="font-size: 7.5pt; color: #45474c;">&nbsp;</span>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Rodapé Fixo -->
        <div class="footer-text">
            Relatório de Célula • Gerado em {{ $generated_at }} pelo usuário {{ auth()->user()->name }} • MDA Church ERP
        </div>
    </div>

</body>
</html>

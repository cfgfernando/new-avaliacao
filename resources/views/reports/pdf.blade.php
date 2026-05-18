<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <title>Relatório de Malote Semanal - MDA Church</title>
    <style>
        @page {
            margin: 1.2cm;
            size: a4 portrait;
        }
        body {
            background-color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #171c1f;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .logo-box {
            width: 45px;
            height: 45px;
            background-color: #1c2434;
            border-radius: 8px;
            text-align: center;
            vertical-align: middle;
            color: #ffffff;
            font-weight: 700;
            font-size: 18pt;
            line-height: 45px;
        }
        .logo-text-title {
            font-size: 15pt;
            font-weight: 700;
            color: #1c2434;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .logo-text-subtitle {
            font-size: 8.5pt;
            color: #45474c;
            font-weight: 600;
            margin: 2px 0 0 0;
        }
        .status-badge {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 9999px;
            display: inline-block;
            letter-spacing: 0.5px;
        }
        .status-conciliated {
            color: #10B981;
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .status-submitted {
            color: #3B82F6;
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        .status-draft {
            color: #ba1a1a;
            background-color: rgba(186, 26, 26, 0.1);
            border: 1px solid rgba(186, 26, 26, 0.2);
        }
        .accent-bar {
            width: 100%;
            height: 3px;
            background-color: #1c2434;
            margin-bottom: 20px;
        }
        .info-bar-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-bar-cell {
            background-color: #f0f4f8;
            border: 1px solid #dfe3e7;
            border-radius: 8px;
            padding: 10px 12px;
        }
        .info-label {
            display: block;
            font-size: 6.5pt;
            font-weight: 700;
            color: #45474c;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 9pt;
            font-weight: 700;
            color: #1c2434;
        }
        .main-layout-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-layout-table td {
            vertical-align: top;
        }
        .panel-financial {
            background-color: #f6fafe;
            border: 1px solid #dfe3e7;
            border-radius: 8px;
            padding: 15px;
            margin-right: 10px;
        }
        .panel-impact {
            background-color: #ffffff;
            border: 1px solid #dfe3e7;
            border-radius: 8px;
            padding: 15px;
            margin-left: 10px;
        }
        .panel-title {
            font-size: 9pt;
            font-weight: 700;
            color: #1c2434;
            text-transform: uppercase;
            margin-bottom: 12px;
            border-bottom: 1px solid #dfe3e7;
            padding-bottom: 6px;
        }
        .financial-total-label {
            font-size: 7.5pt;
            font-weight: 700;
            color: #45474c;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .financial-total-val {
            font-size: 20pt;
            font-weight: 700;
            color: #1c2434;
            margin-bottom: 15px;
        }
        .financial-sub-box {
            background-color: #ffffff;
            border: 1px solid #c6c6cd;
            border-radius: 6px;
            padding: 8px 10px;
            margin-bottom: 8px;
        }
        .financial-sub-label {
            font-size: 6.5pt;
            font-weight: 700;
            color: #45474c;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        .financial-sub-val {
            font-size: 11pt;
            font-weight: 700;
            color: #1c2434;
        }
        .impact-item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .impact-item-cell {
            background-color: #f6fafe;
            border: 1px solid #dfe3e7;
            border-radius: 6px;
            padding: 8px 10px;
        }
        .impact-label {
            font-size: 8pt;
            font-weight: 600;
            color: #171c1f;
        }
        .impact-val {
            font-size: 11pt;
            font-weight: 700;
            color: #1c2434;
            text-align: right;
        }
        .section-title {
            font-size: 9.5pt;
            font-weight: 700;
            color: #1c2434;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .attendance-table th {
            background-color: #eaeef2;
            color: #1c2434;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 7.5pt;
            padding: 8px 12px;
            border-top: 1px solid #c6c6cd;
            border-bottom: 1px solid #c6c6cd;
            text-align: left;
        }
        .attendance-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #dfe3e7;
            font-size: 8.5pt;
            color: #171c1f;
        }
        .badge-present {
            font-size: 7.5pt;
            font-weight: bold;
            color: #10B981;
            background-color: rgba(16, 185, 129, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .footer-signatures {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .signature-line-box {
            border-top: 1px solid #1c2434;
            text-align: center;
            font-size: 8.5pt;
            padding-top: 6px;
        }
        .footer-metadata-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #dfe3e7;
            margin-top: 30px;
            padding-top: 8px;
        }
        .footer-text-left {
            font-size: 7.5pt;
            color: #45474c;
            text-align: left;
        }
        .footer-text-right {
            font-size: 8pt;
            font-weight: 700;
            color: #1c2434;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="55">
                <div class="logo-box">M</div>
            </td>
            <td style="padding-left: 10px;" valign="middle">
                <h1 class="logo-text-title">MDA Church Enterprise</h1>
                <p class="logo-text-subtitle">Relatório de Malote Semanal</p>
            </td>
            <td align="right" valign="middle">
                @if($report->status === 'Conciliated')
                    <span class="status-badge status-conciliated">Validado</span>
                @elseif($report->status === 'Submitted')
                    <span class="status-badge status-submitted">Submetido</span>
                @else
                    <span class="status-badge status-draft">Rascunho</span>
                @endif
                <p style="font-size: 7.5pt; font-family: monospace; color: #45474c; margin: 4px 0 0 0;">
                    ID: #ML-{{ $report->meeting_date?->format('Y') }}-{{ str_pad($report->id, 3, '0', STR_PAD_LEFT) }}
                </p>
            </td>
        </tr>
    </table>
    
    <!-- Accent Line -->
    <div class="accent-bar"></div>

    <!-- Info Grid Bar Table -->
    <table class="info-bar-table" cellpadding="0" cellspacing="8">
        <tr>
            <td width="25%">
                <div class="info-bar-cell">
                    <span class="info-label">Célula</span>
                    <span class="info-value">{{ $report->cell->name }}</span>
                </div>
            </td>
            <td width="25%">
                <div class="info-bar-cell">
                    <span class="info-label">Líder</span>
                    <span class="info-value">{{ $report->cell->leader?->name ?? 'Não Informado' }}</span>
                </div>
            </td>
            <td width="25%">
                <div class="info-bar-cell">
                    <span class="info-label">Data Reunião</span>
                    <span class="info-value">{{ $report->meeting_date?->format('d/m/Y') }}</span>
                </div>
            </td>
            <td width="25%">
                <div class="info-bar-cell">
                    <span class="info-label">Local</span>
                    <span class="info-value">{{ $report->meeting_location ?? 'Sede Central' }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Main Body: Two Columns Table -->
    <table class="main-layout-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Financial Panel (55%) -->
            <td width="55%">
                <div class="panel-financial">
                    <div class="panel-title">Resumo Financeiro</div>
                    <div class="financial-total-label">Total Geral Coletado</div>
                    <div class="financial-total-val">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</div>
                    
                    <div class="financial-sub-box">
                        <span class="financial-sub-label">Via PIX / Cartão</span>
                        <span class="financial-sub-val">R$ {{ number_format($report->offer_pix ?? 0, 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="financial-sub-box" style="margin-bottom: 0;">
                        <span class="financial-sub-label">Em Espécie</span>
                        <span class="financial-sub-val">R$ {{ number_format($report->offer_cash ?? 0, 2, ',', '.') }}</span>
                    </div>
                </div>
            </td>
            
            <!-- Ministerial Impact Panel (45%) -->
            <td width="45%">
                <div class="panel-impact">
                    <div class="panel-title">Impacto Ministerial</div>
                    
                    <table class="impact-item-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="impact-item-cell">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="impact-label">Decisões</td>
                                        <td class="impact-val" style="color: #3B82F6;">{{ str_pad($report->conversions ?? 0, 2, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="impact-item-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="impact-item-cell">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="impact-label">Reconciliações</td>
                                        <td class="impact-val" style="color: #8B5CF6;">{{ str_pad($report->reconciliations ?? 0, 2, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="impact-item-table" cellpadding="0" cellspacing="0" style="margin-bottom: 0;">
                        <tr>
                            <td class="impact-item-cell">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="impact-label">MDAs Realizados</td>
                                        <td class="impact-val" style="color: #10B981;">{{ str_pad($report->mdas_done ?? 0, 2, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Attendance Table Section -->
    <div class="section-title">Lista de Presença e Participação</div>
    <table class="attendance-table">
        <thead>
            <tr>
                <th width="50%">Membro / Visitante</th>
                <th width="30%">Cargo/Função</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presentMembers as $member)
                <tr>
                    <td><strong>{{ $member->user?->name ?? $member->name }}</strong></td>
                    <td>{{ $member->user?->role_label ?? 'Membro' }}</td>
                    <td>
                        <span class="badge-present">Presente</span>
                    </td>
                </tr>
            @endforeach
            
            @php
                $visitorsList = is_array($report->visitor_names) ? $report->visitor_names : json_decode($report->visitor_names, true) ?? [];
            @endphp
            
            @foreach($visitorsList as $visitor)
                @php
                    $vName = is_array($visitor) ? ($visitor['name'] ?? '') : $visitor;
                @endphp
                @if($vName)
                    <tr>
                        <td><strong>{{ $vName }}</strong></td>
                        <td style="color: #575e70; font-style: italic;">Visitante</td>
                        <td>
                            <span class="badge-present">Presente</span>
                        </td>
                    </tr>
                @endif
            @endforeach
            
            @if($presentMembers->count() === 0 && count(array_filter($visitorsList)) === 0)
                <tr>
                    <td colspan="3" align="center" style="color: #76777d; font-style: italic; padding: 15px 0;">
                        Nenhum participante registrado nesta reunião.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Signatures Section -->
    <table class="footer-signatures" cellpadding="0" cellspacing="0">
        <tr>
            <td width="45%">
                <div class="signature-line-box">
                    <strong>{{ $report->submittedBy->name ?? 'Responsável' }}</strong><br/>
                    <span style="font-size: 7.5pt; color: #45474c; text-transform: uppercase;">Líder de Célula</span>
                </div>
            </td>
            <td width="10%"></td>
            <td width="45%">
                <div class="signature-line-box">
                    <strong>Tesouraria / Auditoria</strong><br/>
                    <span style="font-size: 7.5pt; color: #45474c; text-transform: uppercase;">Assinatura / Carimbo</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer metadata -->
    <table class="footer-metadata-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="footer-text-left">
                © {{ date('Y') }} MDA Church Enterprise | Sistema de Gestão Eclesiástica<br/>
                Documento gerado eletronicamente em {{ $generated_at }}
            </td>
            <td class="footer-text-right" valign="bottom">
                Página 01 de 01
            </td>
        </tr>
    </table>

</body>
</html>

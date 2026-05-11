<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>DRE - Demonstração do Resultado do Exercício - {{ $year }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #d4af37;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo-placeholder {
            width: 60px;
            height: 60px;
            background-color: #d4af37;
            border-radius: 8px;
            text-align: center;
            color: #fff;
            font-weight: bold;
            line-height: 60px;
            font-size: 24px;
        }
        .institution-name {
            font-size: 16pt;
            font-weight: bold;
            color: #000;
            margin: 0;
        }
        .report-title {
            font-size: 12pt;
            color: #666;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .content-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-size: 9pt;
            color: #495057;
            text-transform: uppercase;
        }
        .content-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10pt;
        }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .row-total {
            background-color: #fcfcfc;
            font-weight: bold;
        }
        .positive { color: #28a745; }
        .negative { color: #dc3545; }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 8pt;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        
        .section-header {
            background-color: #343a40;
            color: #fff;
            padding: 5px 10px;
            font-size: 10pt;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td width="70">
                    <div class="logo-placeholder">M</div>
                </td>
                <td>
                    <h1 class="institution-name">MDA CHURCH - SISTEMA ERP</h1>
                    <p class="report-title">Demonstração do Resultado do Exercício (DRE)</p>
                </td>
                <td class="text-right" valign="bottom">
                    <p style="font-size: 9pt; color: #666;">Ano Referência: <strong>{{ $year }}</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-header">RESUMO MENSAL DO EXERCÍCIO</div>
    
    <table class="content-table">
        <thead>
            <tr>
                <th>Mês</th>
                <th class="text-right">Receitas (+)</th>
                <th class="text-right">Despesas (-)</th>
                <th class="text-right">Resultado (=)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $month)
            <tr>
                <td>{{ $month['month_name'] }}</td>
                <td class="text-right">R$ {{ number_format($month['revenue'], 2, ',', '.') }}</td>
                <td class="text-right">R$ {{ number_format($month['expense'], 2, ',', '.') }}</td>
                <td class="text-right font-bold {{ $month['result'] >= 0 ? 'positive' : 'negative' }}">
                    R$ {{ number_format($month['result'], 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="row-total">
                <td style="border-top: 2px solid #333;">TOTAL ACUMULADO</td>
                <td class="text-right" style="border-top: 2px solid #333;">R$ {{ number_format($totals['revenue'], 2, ',', '.') }}</td>
                <td class="text-right" style="border-top: 2px solid #333;">R$ {{ number_format($totals['expense'], 2, ',', '.') }}</td>
                <td class="text-right" style="border-top: 2px solid #333; font-size: 12pt;">
                    R$ {{ number_format($totals['result'], 2, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="section-header">DETALHAMENTO POR CONTA CONTÁBIL (SALDO ATUAL)</div>
    
    <table class="content-table" style="font-size: 9pt;">
        <thead>
            <tr>
                <th width="100">Código</th>
                <th>Descrição da Conta</th>
                <th>Tipo</th>
                <th class="text-right">Saldo Acumulado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accountDetails as $account)
            <tr>
                <td>{{ $account->code }}</td>
                <td>{{ $account->name }}</td>
                <td>{{ $account->type == 'Revenue' ? 'Receita' : 'Despesa' }}</td>
                <td class="text-right">R$ {{ number_format($account->current_balance, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <table width="100%">
            <tr>
                <td width="45%" style="border-top: 1px solid #333; text-align: center; padding-top: 5px;">
                    <p style="font-size: 9pt; margin: 0;">Responsável Administrativo</p>
                </td>
                <td width="10%"></td>
                <td width="45%" style="border-top: 1px solid #333; text-align: center; padding-top: 5px;">
                    <p style="font-size: 9pt; margin: 0;">Controle de Auditoria (Conselho)</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Relatório gerado em {{ $generated_at }} · MDA Church ERP · Documento para fins de auditoria interna.
    </div>

</body>
</html>

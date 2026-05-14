<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>DRE - Demonstração do Resultado</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h1 { font-size: 20px; margin: 0; color: #1e293b; text-transform: uppercase; }
        .header p { margin: 4px 0; font-weight: bold; color: #64748b; }
        
        .section-title { background-color: #f1f5f9; padding: 8px; font-weight: bold; text-transform: uppercase; margin-top: 20px; border-left: 4px solid #1e293b; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .indent-1 { padding-left: 20px; }
        .indent-2 { padding-left: 40px; }
        
        .total-row { background-color: #f8fafc; font-weight: bold; font-size: 12px; border-top: 2px solid #e2e8f0; }
        .result-row { background-color: #1e293b; color: white; font-weight: 800; font-size: 14px; margin-top: 30px; padding: 15px; border-radius: 8px; }
        
        .positive { color: #059669; }
        .negative { color: #dc2626; }
        
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; height: 30px; font-size: 9px; color: #94a3b8; text-align: center; }
        @page { margin: 1.5cm; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MDA Church - Gestão ERP</h1>
        <p>Demonstração do Resultado do Exercício (DRE)</p>
        <p>Período: {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <div class="section-title">Receitas Operacionais</div>
    <table>
        @foreach($revenues as $item)
            <tr>
                <td width="80%" class="{{ strlen($item->code) <= 2 ? 'font-bold' : 'indent-1' }}">
                    {{ $item->code }} - {{ $item->name }}
                </td>
                <td width="20%" class="text-right {{ strlen($item->code) <= 2 ? 'font-bold' : '' }}">
                    R$ {{ number_format($item->total, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>TOTAL DAS RECEITAS</td>
            <td class="text-right">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">Despesas Operacionais</div>
    <table>
        @foreach($expenses as $item)
            <tr>
                <td width="80%" class="{{ strlen($item->code) <= 2 ? 'font-bold' : 'indent-1' }}">
                    {{ $item->code }} - {{ $item->name }}
                </td>
                <td width="20%" class="text-right {{ strlen($item->code) <= 2 ? 'font-bold' : '' }}">
                    R$ {{ number_format($item->total, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>TOTAL DAS DESPESAS</td>
            <td class="text-right">R$ {{ number_format($totalExpense, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="result-row">
        <table style="width: 100%; color: white; border: none;">
            <tr style="border: none;">
                <td style="border: none; font-size: 14px;">RESULTADO LÍQUIDO DO PERÍODO</td>
                <td style="border: none; text-align: right; font-size: 18px;">
                    R$ {{ number_format($netResult, 2, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Gerado em: {{ $generated_at }} | Este documento é para fins de auditoria interna.
    </div>
</body>
</html>

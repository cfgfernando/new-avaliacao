<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Balancete de Verificação</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin: 0; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 2px 0; font-weight: bold; color: #64748b; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 8px; border: 1px solid #e2e8f0; }
        td { padding: 6px 8px; border: 1px solid #f1f5f9; }
        
        .row-parent { background-color: #f1f5f9; font-weight: bold; }
        .row-grandparent { background-color: #e2e8f0; font-weight: 800; font-size: 10px; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .positive { color: #059669; }
        .negative { color: #dc2626; }
        
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; height: 30px; font-size: 8px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 5px; }
        
        @page { margin: 1cm; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MDA Church - Gestão ERP</h1>
        <p>Balancete de Verificação</p>
        <p>Período: {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="12%">Código</th>
                <th width="38%">Conta Contábil</th>
                <th width="12.5%" class="text-right">Saldo Anterior</th>
                <th width="12.5%" class="text-right">Débito</th>
                <th width="12.5%" class="text-right">Crédito</th>
                <th width="12.5%" class="text-right">Saldo Atual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trialBalance as $item)
                @php
                    $isParent = strlen($item->code) <= 4;
                    $isGrandParent = strlen($item->code) <= 1;
                @endphp
                <tr class="{{ $isGrandParent ? 'row-grandparent' : ($isParent ? 'row-parent' : '') }}">
                    <td>{{ $item->code }}</td>
                    <td style="padding-left: {{ (strlen($item->code) - 1) * 5 }}px">
                        {{ $item->name }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->opening_balance, 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->debit, 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->credit, 2, ',', '.') }}
                    </td>
                    <td class="text-right font-bold">
                        {{ number_format($item->closing_balance, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Gerado em: {{ $generated_at }} | Página 1 de 1
    </div>
</body>
</html>

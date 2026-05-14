<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Balanço Patrimonial</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin: 0; color: #1e293b; text-transform: uppercase; }
        .header p { margin: 2px 0; font-weight: bold; color: #64748b; }
        
        .container { width: 100%; }
        .column { width: 48%; float: left; }
        .column-right { width: 48%; float: right; }
        .clear { clear: both; }
        
        .section-title { background-color: #1e293b; color: white; padding: 6px; font-weight: bold; text-transform: uppercase; margin-top: 15px; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        td { padding: 5px; border-bottom: 1px solid #f1f5f9; }
        
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .indent-1 { padding-left: 15px; }
        
        .total-box { background-color: #f1f5f9; padding: 10px; border: 1px solid #e2e8f0; margin-top: 10px; font-weight: bold; }
        
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; height: 30px; font-size: 8px; color: #94a3b8; text-align: center; }
        @page { margin: 1cm; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MDA Church - Gestão ERP</h1>
        <p>Balanço Patrimonial</p>
        <p>Posição em: {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <div class="container">
        <!-- ATIVO -->
        <div class="column">
            <div class="section-title">ATIVO (Bens e Direitos)</div>
            <table>
                @foreach($assets as $item)
                    <tr>
                        <td width="70%" class="{{ strlen($item->code) <= 2 ? 'font-bold' : 'indent-1' }}">
                            {{ $item->code }} - {{ $item->name }}
                        </td>
                        <td width="30%" class="text-right {{ strlen($item->code) <= 2 ? 'font-bold' : '' }}">
                            {{ number_format($item->total, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </table>
            <div class="total-box">
                <table style="width:100%">
                    <tr>
                        <td>TOTAL DO ATIVO</td>
                        <td class="text-right">R$ {{ number_format($totalAsset, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- PASSIVO E PL -->
        <div class="column-right">
            <div class="section-title">PASSIVO (Obrigações)</div>
            <table>
                @foreach($liabilities as $item)
                    <tr>
                        <td width="70%" class="{{ strlen($item->code) <= 2 ? 'font-bold' : 'indent-1' }}">
                            {{ $item->code }} - {{ $item->name }}
                        </td>
                        <td width="30%" class="text-right {{ strlen($item->code) <= 2 ? 'font-bold' : '' }}">
                            {{ number_format($item->total, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </table>

            <div class="section-title">PATRIMÔNIO LÍQUIDO</div>
            <table>
                @foreach($equity as $item)
                    <tr>
                        <td width="70%" class="{{ strlen($item->code) <= 2 ? 'font-bold' : 'indent-1' }}">
                            {{ $item->code }} - {{ $item->name }}
                        </td>
                        <td width="30%" class="text-right {{ strlen($item->code) <= 2 ? 'font-bold' : '' }}">
                            {{ number_format($item->total, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </table>

            <div class="total-box">
                <table style="width:100%">
                    <tr>
                        <td>TOTAL PASSIVO + PL</td>
                        <td class="text-right">R$ {{ number_format($totalLiability + $totalEquity, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div class="footer">
        Gerado em: {{ $generated_at }} | Página 1 de 1
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Relatório de Malote Semanal - MDA Church</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "background": "#f6fafe",
                        "on-secondary": "#ffffff",
                        "outline-variant": "#c6c6cd",
                        "on-primary-fixed-variant": "#3f4758",
                        "on-tertiary-fixed": "#2a1700",
                        "on-primary-fixed": "#131c2b",
                        "on-error": "#ffffff",
                        "primary-fixed-dim": "#bec6dc",
                        "secondary-fixed-dim": "#c0c6db",
                        "secondary": "#575e70",
                        "tertiary": "#190c00",
                        "on-primary-container": "#838b9f",
                        "error-container": "#ffdad6",
                        "on-surface": "#171c1f",
                        "surface-variant": "#dfe3e7",
                        "semantic-blue": "#3B82F6",
                        "on-secondary-fixed": "#141b2b",
                        "primary": "#060e1e",
                        "inverse-primary": "#bec6dc",
                        "secondary-container": "#d9dff5",
                        "inverse-surface": "#2c3134",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed": "#dce2f7",
                        "on-tertiary-container": "#c27c00",
                        "on-error-container": "#93000a",
                        "surface-bright": "#f6fafe",
                        "surface-tint": "#565e71",
                        "on-tertiary-fixed-variant": "#653e00",
                        "surface-container": "#eaeef2",
                        "tertiary-fixed-dim": "#ffb95f",
                        "surface-container-highest": "#dfe3e7",
                        "on-secondary-fixed-variant": "#404758",
                        "on-primary": "#ffffff",
                        "surface-container-high": "#e4e9ed",
                        "primary-fixed": "#dbe2f8",
                        "tertiary-container": "#361f00",
                        "surface-dim": "#d6dade",
                        "error": "#ba1a1a",
                        "tertiary-fixed": "#ffddb8",
                        "semantic-green": "#10B981",
                        "primary-container": "#1c2434",
                        "semantic-purple": "#8B5CF6",
                        "on-secondary-container": "#5c6274",
                        "inverse-on-surface": "#edf1f5",
                        "outline": "#76777d",
                        "on-surface-variant": "#45474c",
                        "surface": "#FFFFFF",
                        "surface-container-low": "#f0f4f8",
                        "on-tertiary": "#ffffff",
                        "on-background": "#171c1f"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "space-md": "1rem",
                        "gutter": "16px",
                        "space-xl": "2rem",
                        "space-xs": "0.25rem",
                        "container-margin": "24px",
                        "space-sm": "0.5rem",
                        "space-lg": "1.5rem"
                    },
                    "fontFamily": {
                        "label-md": ["Libre Franklin"],
                        "headline-md": ["Libre Franklin"],
                        "headline-lg": ["Libre Franklin"],
                        "label-lg": ["Libre Franklin"],
                        "body-lg": ["Libre Franklin"],
                        "headline-xl": ["Libre Franklin"],
                        "button-label": ["Libre Franklin"],
                        "body-md": ["Libre Franklin"]
                    },
                    "fontSize": {
                        "label-md": ["13px", {"lineHeight": "18px", "fontWeight": "500"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                        "label-lg": ["15px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "headline-xl": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "button-label": ["17px", {"lineHeight": "24px", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
    <style>
        /* Margens padronizadas e limpas para a folha física A4 */
        @page {
            size: A4;
            margin: 12mm 15mm !important;
        }
        
        /* Regras exclusivas para visualização na Tela (Preview do ERP) */
        @media screen {
            body {
                background-color: #d6dade !important;
                padding: 2rem !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
            }
            .a4-page {
                width: 210mm !important;
                height: 297mm !important;
                min-height: 297mm !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
                border: 1px solid #c6c6cd !important;
                background-color: white !important;
                margin: auto !important;
                flex-shrink: 0 !important;
                padding: 15mm !important;
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
            }
            .inner-container {
                height: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                box-sizing: border-box !important;
                flex-grow: 1 !important;
            }
        }
        
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Libre Franklin', sans-serif; }
        .inner-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
    </style>
</head>
<body class="text-on-surface bg-gray-100 flex flex-col items-center">

<!-- UI Controls (Non-Printable) -->
<div class="no-print w-full max-w-[210mm] flex justify-between mb-6 mt-4 items-center">
    <div class="flex items-center gap-3">
        <a class="flex items-center justify-center p-2 rounded-full bg-white text-primary border border-outline-variant hover:bg-surface-container transition-all" href="{{ route('reports.show', $report) }}">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-primary font-bold text-lg">Visualização de Exportação</h1>
    </div>
    <div class="flex gap-2">
        <button class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-full font-semibold hover:bg-opacity-90 transition-all shadow-sm" onclick="window.print()">
            <span class="material-symbols-outlined">print</span>
            <span>Imprimir Relatório</span>
        </button>
        <button class="flex items-center gap-2 px-6 py-2 bg-white text-primary border border-outline-variant rounded-full font-semibold hover:bg-surface-container transition-all" onclick="window.print()">
            <span class="material-symbols-outlined">download</span>
            <span>Baixar PDF</span>
        </button>
    </div>
</div>

<!-- A4 Document Container -->
<div class="a4-page w-[210mm] h-[297mm] bg-white shadow-2xl overflow-hidden flex flex-col p-[15mm] relative box-border m-auto shrink-0">
    <!-- Inner content container that stretches dynamically -->
    <div class="inner-container">
        
        <div>
            <!-- Header / Branding -->
            <div class="flex justify-between items-start border-b-2 border-primary pb-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-primary flex items-center justify-center rounded-lg">
                        <span class="material-symbols-outlined text-white text-4xl">church</span>
                    </div>
                    <div>
                        <h1 class="text-headline-md font-bold text-primary uppercase tracking-tight">Relatório de Malote</h1>
                        <p class="text-label-md text-outline">SISTEMA DE GESTÃO ECLÉSIA</p>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end gap-1">
                    @if($report->status === 'Conciliated')
                        <span class="bg-semantic-green bg-opacity-10 text-semantic-green px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-semantic-green border-opacity-20 mb-1">Validado</span>
                    @elseif($report->status === 'Submitted')
                        <span class="bg-accent-orange bg-opacity-10 text-amber-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-amber-500 border-opacity-20 mb-1">Submetido</span>
                    @else
                        <span class="bg-error bg-opacity-10 text-error px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-error border-opacity-20 mb-1">Rascunho</span>
                    @endif
                    <p class="text-label-lg font-bold text-primary">Célula {{ $report->cell->name }}</p>
                    <p class="text-body-md text-on-surface-variant">Líder: {{ $report->cell->leader?->name ?? 'Não Informado' }}</p>
                </div>
            </div>

            <!-- Meeting Info Grid -->
            <div class="grid grid-cols-2 gap-8 mb-8 pb-4 border-b border-outline-variant">
                <div>
                    <p class="text-label-md text-outline uppercase">Data da Reunião</p>
                    <p class="text-body-lg font-semibold">{{ $report->meeting_date?->translatedFormat('d \d\e F \d\e Y') ?? $report->meeting_date?->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-label-md text-outline uppercase">Local / Sede</p>
                    <p class="text-body-lg font-semibold">{{ $report->meeting_location ?? 'Sede Central - Campus Principal' }}</p>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <!-- Financial Column -->
                <div class="space-y-4">
                    <h3 class="text-label-lg font-bold text-primary uppercase border-b border-outline-variant pb-2">Resumo Financeiro</h3>
                    <div class="p-4 border border-outline-variant rounded-lg">
                        <div class="mb-4">
                            <p class="text-label-md text-outline">Total Geral Coletado</p>
                            <p class="text-headline-md font-bold text-primary">R$ {{ number_format($report->total_offer, 2, ',', '.') }}</p>
                        </div>
                        <div class="space-y-2 text-body-md border-t border-outline-variant pt-3">
                            <div class="flex justify-between">
                                <span>Via PIX / Cartão</span>
                                <span class="font-semibold">R$ {{ number_format($report->offer_pix ?? 0, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Em Espécie</span>
                                <span class="font-semibold">R$ {{ number_format($report->offer_cash ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Frequency Column -->
                <div class="space-y-4">
                    <h3 class="text-label-lg font-bold text-primary uppercase border-b border-outline-variant pb-2">Frequência</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 border border-outline-variant rounded-lg text-center">
                            <p class="text-headline-sm font-bold">{{ str_pad($report->present_members ?? 0, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-label-md text-outline">Membros</p>
                        </div>
                        <div class="p-3 border border-outline-variant rounded-lg text-center">
                            <p class="text-headline-sm font-bold">{{ str_pad($report->visitors ?? 0, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-label-md text-outline">Visitantes</p>
                        </div>
                        <div class="p-3 border border-outline-variant rounded-lg text-center">
                            <p class="text-headline-sm font-bold">{{ str_pad($report->children ?? 0, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-label-md text-outline">Crianças</p>
                        </div>
                        <div class="p-3 border border-outline-variant rounded-lg text-center bg-surface-container-low">
                            <p class="text-headline-sm font-bold">{{ str_pad($report->total_presence ?? 0, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-label-md text-primary font-bold">Total Geral</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Impact and Attendance -->
            <div class="space-y-8">
                <section>
                    <h3 class="text-label-lg font-bold text-primary uppercase border-b border-outline-variant pb-2 mb-4">Impacto Ministerial</h3>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-semantic-purple">campaign</span>
                            <span class="text-body-md">Novas Decisões: <b>{{ str_pad($report->conversions ?? 0, 2, '0', STR_PAD_LEFT) }}</b></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-semantic-blue">handshake</span>
                            <span class="text-body-md">Reconciliações: <b>{{ str_pad($report->reconciliations ?? 0, 2, '0', STR_PAD_LEFT) }}</b></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-semantic-green">home</span>
                            <span class="text-body-md">Casas de Paz: <b>{{ str_pad($report->house_of_peace ?? 0, 2, '0', STR_PAD_LEFT) }}</b></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-tertiary">school</span>
                            <span class="text-body-md">Discipulados: <b>{{ str_pad($report->mdas_done ?? 0, 2, '0', STR_PAD_LEFT) }}</b></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-error">volunteer_activism</span>
                            <span class="text-body-md">Quilo do Amor: <b>{{ number_format($report->kg_of_love ?? 0, 1, ',', '.') }} kg</b></span>
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="text-label-lg font-bold text-primary uppercase border-b border-outline-variant pb-2 mb-4">Lista de Presença</h3>
                    <div class="border border-outline-variant rounded-lg overflow-hidden bg-white">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant">
                                    <th class="p-3 text-label-md">Nome Completo</th>
                                    <th class="p-3 text-label-md">Função / Cargo</th>
                                    <th class="p-3 text-label-md">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/30">
                                @foreach($allCellMembers as $cellMember)
                                    @php
                                        $isPresent = in_array($cellMember->id, $memberIds);
                                        $fullName = $cellMember->user?->name ?? $cellMember->name;
                                    @endphp
                                    <tr class="border-b border-outline-variant">
                                        <td class="p-3 text-body-md font-semibold text-primary">{{ $fullName }}</td>
                                        <td class="p-3 text-body-md text-on-surface-variant">{{ $cellMember->user?->role_label ?? 'Membro' }}</td>
                                        <td class="p-3 text-body-md font-bold {{ $isPresent ? 'text-semantic-green' : 'text-error' }}">
                                            {{ $isPresent ? 'Presente' : 'Ausente' }}
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach($visitorsList as $visitor)
                                    @php
                                        $vName = is_array($visitor) ? ($visitor['name'] ?? '') : $visitor;
                                    @endphp
                                    @if($vName)
                                        <tr class="border-b border-outline-variant">
                                            <td class="p-3 text-body-md font-semibold text-primary">{{ $vName }}</td>
                                            <td class="p-3 text-body-md text-on-surface-variant italic">Visitante</td>
                                            <td class="p-3 text-body-md font-bold text-semantic-green">Presente</td>
                                        </tr>
                                    @endif
                                @endforeach

                                @if($allCellMembers->count() === 0 && count(array_filter($visitorsList)) === 0)
                                    <tr class="border-b border-outline-variant">
                                        <td colspan="3" class="p-3 text-center text-body-md text-outline italic">
                                            Nenhum participante registrado nesta reunião.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
        
        <!-- Footer / Signatures -->
        <div class="absolute bottom-[15mm] left-[15mm] right-[15mm]">
            <div class="grid grid-cols-2 gap-12 mb-8">
                <div class="text-center">
                    <div class="border-b border-outline mb-2"></div>
                    <p class="text-label-md text-outline uppercase">Assinatura Líder: {{ $report->submittedBy->name ?? 'Líder da Célula' }}</p>
                </div>
                <div class="text-center">
                    <div class="border-b border-outline mb-2"></div>
                    <p class="text-label-md text-outline uppercase">Tesouraria / Auditoria</p>
                </div>
            </div>
            <p class="text-[10px] text-center text-outline-variant uppercase tracking-[0.2em] font-medium">
                Documento Validado Digitalmente via Sistema Eclésia • Gerado em {{ $generated_at }}
            </p>
        </div>
        
    </div>
</div>

</body>
</html>

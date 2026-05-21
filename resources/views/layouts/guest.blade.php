<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema Padrão') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;650;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-background">
        <div class="min-h-screen flex flex-col sm:justify-center items-center relative overflow-hidden px-4">
            <!-- Background Elements -->
            <div class="absolute inset-0 z-0 bg-slate-50">
                <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-[#2563eb]/5 blur-[120px]"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-500/5 blur-[120px]"></div>
            </div>

            <div class="relative z-10 w-full flex flex-col items-center py-12">
                <div class="mb-8 text-center px-4">
                    <a href="/" class="flex flex-col items-center gap-3 group">
                        <div class="w-14 h-14 bg-[#2563eb] rounded-xl flex items-center justify-center text-white text-2xl shadow-md shadow-[#2563eb]/10 transition-transform group-hover:scale-105 duration-300">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-3 font-sans">
                            Sistema <span class="text-[#2563eb]">Padrão</span>
                        </h1>
                        <p class="text-slate-400 font-black text-[10px] uppercase tracking-widest font-mono mt-1">Painel de Controle Administrativo</p>
                    </a>
                </div>

                <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-md border border-slate-200 rounded-xl overflow-hidden relative transition-all duration-300">
                    <!-- Decorative Top Border -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-[#2563eb]"></div>
                    
                    {{ $slot }}
                </div>

                <div class="mt-8 text-center text-slate-400 text-xs font-mono">
                    &copy; {{ date('Y') }} Sistema Padrão. <span class="text-[#2563eb]/70 font-bold">Tecnologia e Performance.</span>
                </div>
            </div>
        </div>
    </body>
</html>

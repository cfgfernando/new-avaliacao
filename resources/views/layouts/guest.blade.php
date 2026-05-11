<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MDA CHURCH') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Montserrat:wght@800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-primary-dark text-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#0f151f] selection:bg-accent selection:text-white">
        <div class="animate-fade-up">
            <a href="/" class="flex flex-col items-center gap-4 group">
                <div class="w-16 h-16 gold-gradient rounded-2xl flex items-center justify-center shadow-accent transition-transform group-hover:scale-110">
                    <i class="fas fa-church text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-display font-extrabold text-white tracking-tighter uppercase">MDA <span class="text-accent">CHURCH</span></h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-primary border border-white/5 shadow-2xl rounded-2xl animate-fade-up" style="animation-delay: 0.1s">
            {{ $slot }}
        </div>

        <div class="mt-8 text-gray-600 text-[10px] font-bold uppercase tracking-[0.2em] animate-fade-up" style="animation-delay: 0.2s">
            Elite V8 Security & Facilities Pattern
        </div>
    </div>
</body>
</html>

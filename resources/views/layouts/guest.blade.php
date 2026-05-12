<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MDA Church ERP') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-primary-dark antialiased bg-background">
        <div class="min-h-screen flex flex-col sm:justify-center items-center relative overflow-hidden">
            <!-- BACKGROUND IMAGE WITH OVERLAY -->
            <div class="absolute inset-0 z-0 bg-primary">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/Interior_of_the_Cathedral_of_Bras%C3%ADlia.jpg/1920px-Interior_of_the_Cathedral_of_Bras%C3%ADlia.jpg" alt="Background" class="w-full h-full object-cover opacity-60 mix-blend-overlay">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/80 via-transparent to-accent/20"></div>
            </div>

            <div class="relative z-10 w-full flex flex-col items-center py-12">
                <div class="mb-8 sm:mb-10 text-center animate-reveal-up px-4">
                    <a href="/" class="flex flex-col items-center gap-4 group">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-accent rounded-2xl flex items-center justify-center text-white text-3xl sm:text-4xl shadow-2xl shadow-accent/40 transition-transform group-hover:scale-105 group-hover:rotate-3 duration-500">
                            <i class="fas fa-church"></i>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black text-white uppercase tracking-tighter mt-2 sm:mt-4">
                            MDA <span class="text-accent">Church</span>
                        </h1>
                        <p class="text-accent/80 font-bold text-[10px] sm:text-xs uppercase tracking-[0.2em] sm:tracking-[0.4em] mt-1 sm:mt-2">Enterprise Resource Planning</p>
                    </a>
                </div>

                <div class="w-[calc(100%-2rem)] sm:w-full sm:max-w-md mx-4 sm:mx-0 px-6 sm:px-10 py-8 sm:py-12 bg-white/90 backdrop-blur-2xl shadow-[0_0_60px_-15px_rgba(0,0,0,0.5)] shadow-accent/10 rounded-3xl sm:rounded-[2rem] border border-white/40 overflow-hidden relative animate-reveal-up delay-100 hover:shadow-accent/20 transition-all duration-500 sm:hover:-translate-y-1">
                    <!-- Decorative Element -->
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-accent to-accent-hover shadow-lg shadow-accent/50"></div>
                    
                    {{ $slot }}
                </div>

                <div class="mt-12 text-center text-white/50 text-xs font-medium animate-reveal-up delay-200">
                    &copy; {{ date('Y') }} MDA Church ERP. <span class="text-accent/60">Tecnologia para o Reino.</span>
                </div>
            </div>
        </div>
    </body>
</html>

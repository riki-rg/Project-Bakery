<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bakery System') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-canvas">
        <!-- Split Auth Layout: Desktop uses golden ratio grid, Mobile stacks -->
        <div class="min-h-screen grid lg:grid-cols-[minmax(0,1fr)_minmax(0,1.618fr)]">
            
            <!-- Left Panel: Form Area -->
            <div class="flex items-center justify-center p-6 lg:p-12 bg-surface">
                <div class="w-full max-w-auth">
                    <!-- Brand Mark -->
                    <div class="mb-8">
                        <a href="/" class="inline-flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-display font-bold text-xl text-text-main">Bakery</div>
                                <div class="text-xs text-text-muted font-semibold uppercase tracking-wide">Panel Operasional</div>
                            </div>
                        </a>
                    </div>

                    <!-- Content Slot -->
                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right Panel: Visual Brand Area (Desktop Only) -->
            <div class="hidden lg:flex items-center justify-center p-12 bg-surface-strong relative overflow-hidden">
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-transparent to-accent/10"></div>
                
                <!-- Content -->
                <div class="relative z-10 text-center max-w-lg">
                    <!-- Hero Icon -->
                    <div class="mb-8 flex justify-center">
                        <div class="w-24 h-24 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Tagline -->
                    <h1 class="display-md text-text-inverse mb-4">
                        Kelola Toko Roti Anda
                    </h1>
                    <p class="body-lg text-text-inverse/80">
                        Sistem manajemen terpadu untuk operasional toko roti modern. Pantau stok, kelola karyawan, dan tingkatkan produktivitas.
                    </p>
                    
                    <!-- Decorative Elements -->
                    <div class="mt-12 flex justify-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-white/40"></div>
                        <div class="w-2 h-2 rounded-full bg-white/60"></div>
                        <div class="w-2 h-2 rounded-full bg-white/80"></div>
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    </div>
                </div>
                
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <pattern id="bakery-pattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                            <circle cx="50" cy="50" r="30" fill="white" opacity="0.1"/>
                            <circle cx="50" cy="50" r="20" fill="white" opacity="0.1"/>
                        </pattern>
                        <rect width="100%" height="100%" fill="url(#bakery-pattern)"/>
                    </svg>
                </div>
            </div>

        </div>
    </body>
</html>

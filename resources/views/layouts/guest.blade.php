<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 pb-8 bg-gray-100 dark:bg-gray-900">
            <div class="w-full sm:max-w-md px-6 flex justify-center overflow-hidden">
                <a href="/" class="block w-full max-h-[90px]">
                    <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="w-full h-auto max-h-[90px] object-contain object-center" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <p class="mt-auto pt-6 text-center text-sm text-gray-500 dark:text-gray-400">© 2026 EG. All rights reserved.</p>
        </div>
    </body>
</html>

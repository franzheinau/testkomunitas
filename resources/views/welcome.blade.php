<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- font / vite -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css','resources/js/app.js'])
    @else
        <!-- fallback minimal CSS (optional) -->
        <style>body{font-family:Instrument Sans,system-ui,sans-serif;margin:0;background:#f7f7fb;color:#111}</style>
    @endif
</head>
<body class="antialiased min-h-screen flex flex-col">

    {{-- NAV --}}
    <header class="w-full bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-lg font-semibold text-indigo-600">{{ config('app.name') }}</a>
                <nav class="hidden md:flex gap-4 text-sm text-gray-600">
                    <a href="#" class="hover:underline"></a>
                    <a href="#" class="hover:underline"></a>
                    <a href="#" class="hover:underline"></a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-indigo-600 border border-indigo-200 rounded-md">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md ml-2">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <main class="flex-1">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Selamat Datang Di Komunitas Pthree</h1>
                <p class="text-lg text-gray-600 mb-6">Komunitas Baca dari SMAN 2 Muara Teweh </p>

                <div class="flex flex-wrap gap-3">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-3 bg-indigo-600 text-white rounded-md shadow">Langsung Register aja Yuks</a>
                    @endif
                    <a href="https://www.pthreesmada.my.id" class="inline-flex items-center px-5 py-3 border border-gray-200 text-gray-700 rounded-md">Cek Website Kami Yang lain</a>
                </div>

                <div class="mt-8 grid grid-cols-3 gap-4 text-sm text-gray-600">
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="font-semibold">Literasi</div>
                        <div class="text-xs">Meningkatkan Literasi dan wawasan membaca</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="font-semibold">Kebersamaan</div>
                        <div class="text-xs">Membangun kebersamaan dalam komunitas</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="font-semibold">Keceriaan</div>
                        <div class="text-xs">Membangun keceriaan dan semangat bersama</div>
                    </div>
                </div>
            </div>
            <div>
                <img src="https://ik.imagekit.io/3fgrgeqi6z/pthree.png?updatedAt=1764753926583" alt="Reading Community" class="w-full rounded-lg shadow-lg" />
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 flex items-center justify-between">
            <div>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
            <div class="flex gap-4">
                <a href="#" class="hover:underline">Privacy</a>
                <a href="{{ route('terms') }}" class="hover:underline">Terms</a>
            </div>
        </div>
    </footer>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
</body>
</html>

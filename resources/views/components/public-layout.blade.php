<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? config('app.name') }}</title>
  {{-- kalau pakai Vite --}}
  @vite('resources/css/app.css')
  @stack('styles')
</head>
<body class="antialiased bg-slate-50">


  <main class="min-h-screen">
    {{ $slot }}
  </main>

  @vite('resources/js/app.js')
  @stack('scripts')
</body>
</html>

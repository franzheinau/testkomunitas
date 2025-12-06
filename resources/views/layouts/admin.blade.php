<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>{{ $title ?? config('app.name') . ' - Admin' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 antialiased">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-800 border-r border-slate-700 min-h-screen transition-transform duration-200 ease-in-out">
      <div class="p-4 flex items-center gap-3 border-b border-slate-700">
        <div class="flex items-center gap-3">
          {{-- optional logo area --}}
          <div class="h-9 w-9 rounded-md bg-indigo-600 flex items-center justify-center text-white font-bold">P</div>
          <div class="text-2xl font-semibold tracking-tight">{{ config('app.name') }}</div>
        </div>
      </div>

      <nav class="p-4">
        <ul class="space-y-1">
          <li>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-700 transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 ring-1 ring-indigo-500' : '' }}">
              <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg>
              <span class="text-sm">Dashboard</span>
            </a>
          </li>

          @hasrole('super-admin|admin')
          <li>
            <a href="#users" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-700 transition">
              <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8z"/></svg>
              <span class="text-sm">Users</span>
            </a>
          </li>
          @endhasrole

          @can('events.create')
          <li>
            <a href="{{ route('events.create') ?? '#' }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-700 transition">
              <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M3 11h18M5 11v10h14V11"/></svg>
              <span class="text-sm">Buat Event</span>
            </a>
          </li>
          @endcan

          <li class="mt-4">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-700 transition text-sm">
                  Logout
                </button>
              </form>
          </li>
        </ul>
      </nav>

      {{-- optional footer in sidebar --}}
      <div class="mt-auto p-4 text-xs text-slate-400">
        <div>{{ config('app.name') }} • Admin</div>
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 min-h-screen flex flex-col">
      <!-- Topbar -->
      <header class="bg-slate-900 border-b border-slate-700 p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-4">
          <button id="sidebar-toggle" class="sm:hidden p-2 rounded-md hover:bg-slate-800 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>

          {{-- Back to dashboard button --}}
          <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali ke Dashboard</span>
          </a>

          <h1 class="text-xl font-semibold">{{ $title ?? 'Admin Dashboard' }}</h1>
        </div>

        <div class="flex items-center gap-4">
          {{-- User name + small avatar --}}
          <div class="flex items-center gap-3">
            <div class="hidden md:block text-sm text-slate-300">{{ Auth::user()->name }}</div>
            <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
              {{-- optional initials --}}
              {{ strtoupper(substr(Auth::user()->name, 0, 1) ?? 'U') }}
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="p-6 grow overflow-auto">
        <div class="max-w-7xl mx-auto">
          {{-- content card wrapper to match theme --}}
          <div class="bg-slate-800/40 border border-slate-700 rounded-2xl p-6 shadow-lg">
            {{ $slot ?? '' }}
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    // small sidebar toggle for mobile
    document.addEventListener('DOMContentLoaded', function () {
      const btn = document.getElementById('sidebar-toggle');
      const aside = document.querySelector('aside');
      if (btn && aside) {
        btn.addEventListener('click', () => {
          aside.classList.toggle('-translate-x-full');
        });
      }
    });
  </script>
</body>
</html>

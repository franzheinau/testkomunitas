<nav x-data="{ open: false }" x-cloak class="fixed inset-x-0 top-0 z-50">
  <div class="backdrop-blur-sm bg-white/80 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <div class="flex items-center gap-4">
          <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3">
            <x-application-logo class="block h-10 w-auto fill-current text-indigo-600" />
            <span class="hidden md:inline-block text-indigo-600 font-semibold text-lg">Pthree</span>
          </a>

          {{-- Desktop links --}}
          <div class="hidden lg:flex items-center gap-2">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
              {{ __('Dashboard') }}
            </x-nav-link>

            <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
              {{ __('Post Karya') }}
            </x-nav-link>

            @role('super-admin')
              <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Admin Dashboard') }}
              </x-nav-link>
            @endrole

            @hasrole('manager|admin')
              <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')">
                {{ __('Manajemen Tim') }}
              </x-nav-link>
            @endhasrole

            @can('events.create')
              <x-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')">
                {{ __('Buat Event') }}
              </x-nav-link>
            @endcan
          </div>
        </div>

        <div class="flex items-center gap-4">
          {{-- quick action for admin --}}
          @role('admin|super-admin')
            <a href="{{ route('posts.create') }}?type=announcement"
               class="hidden md:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-shadow shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Buat Pengumuman
            </a>
          @endrole

          {{-- Account dropdown (desktop) --}}
          <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
              <x-slot name="trigger">
                <button class="inline-flex items-center gap-3 px-3 py-2 border border-gray-200 rounded-xl bg-white/80 backdrop-blur-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                  <div class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</div>
                  <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </x-slot>

              <x-slot name="content">
                <x-dropdown-link :href="route('users.show', Auth::user()->id)">
                  {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                  </x-dropdown-link>
                </form>
              </x-slot>
            </x-dropdown>
          </div>

          {{-- Mobile hamburger --}}
          <div class="sm:hidden">
            <button @click="open = true" aria-label="Open menu"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100/60 focus:outline-none transition">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MOBILE: overlay + slide panel --}}
  <div x-show="open" x-cloak
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 z-40">
    {{-- overlay --}}
    <div @click="open = false" class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

    {{-- panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition transform duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition transform duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="absolute right-0 top-0 bottom-0 w-[90%] max-w-sm bg-white shadow-2xl border-l border-gray-100 overflow-y-auto">
      <div class="p-4">
        <div class="flex items-center justify-between">
          <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3">
            <x-application-logo class="h-8 w-auto fill-current text-indigo-600" />
            <span class="text-indigo-600 font-semibold">Pthree</span>
          </a>

          <button @click="open = false" aria-label="Close menu" class="p-2 rounded-lg hover:bg-gray-100">
            <svg class="h-6 w-6 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18L18 6"/>
            </svg>
          </button>
        </div>

        <nav class="mt-6 space-y-1">
          <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-responsive-nav-link>

          <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
            {{ __('Post Karya') }}
          </x-responsive-nav-link>

          @role('super-admin')
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
              {{ __('Admin Dashboard') }}
            </x-responsive-nav-link>
          @endrole

          @hasrole('manager|admin')
            <x-responsive-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')">
              {{ __('Manajemen Tim') }}
            </x-responsive-nav-link>
          @endhasrole

          @can('events.create')
            <x-responsive-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')">
              {{ __('Buat Event') }}
            </x-responsive-nav-link>
          @endcan

          @role('admin|super-admin')
            <a href="{{ route('posts.create') }}?type=announcement" class="block mt-3 px-3 py-2 rounded-lg bg-indigo-600 text-white text-center font-semibold">
              + Buat Pengumuman
            </a>
          @endrole
        </nav>

        <div class="mt-6 pt-4 border-t border-gray-100">
          <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</div>
          <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>

          <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('users.show', Auth::user()->id)">
              {{ __('Profile') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
              </x-responsive-nav-link>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>

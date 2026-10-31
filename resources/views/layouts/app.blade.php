<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gudang Kopi - Inventory System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800 antialiased min-h-screen" x-data="{ open: false }">
    <nav class="bg-amber-950 text-amber-50 shadow-lg" x-data="{ mobileOpen: false }">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('dashboard') }}" class="text-lg sm:text-xl font-bold tracking-wide flex items-center gap-2">
                    <span>☕</span>
                    <span class="hidden sm:inline">Sistem Gudang Kopi</span>
                    <span class="sm:hidden">Gudang Kopi</span>
                </a>

                @auth
                <!-- Desktop menu -->
                <div class="hidden md:flex items-center space-x-3">
                    <a href="{{ route('inventory.index') }}" class="text-sm hover:text-amber-200 transition {{ request()->routeIs('inventory.*') ? 'text-amber-200 font-semibold' : '' }}">
                        Stok Kopi
                    </a>
                    <span class="text-sm bg-amber-800 px-3 py-1 rounded-full text-amber-200">
                        {{ Auth::user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs bg-rose-700 hover:bg-rose-800 text-white px-3 py-1.5 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>

                <!-- Hamburger -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-md text-amber-200 hover:text-white hover:bg-amber-900 focus:outline-none transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                @endauth
            </div>

            @auth
            <!-- Mobile menu -->
            <div x-show="mobileOpen" x-transition class="md:hidden pb-4 space-y-2">
                <a href="{{ route('inventory.index') }}" class="block px-3 py-2 rounded-md text-sm hover:bg-amber-900 {{ request()->routeIs('inventory.*') ? 'bg-amber-900 font-semibold' : '' }}">
                    Stok Kopi
                </a>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-sm hover:bg-amber-900">
                    Dashboard
                </a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm hover:bg-amber-900">
                    Profil
                </a>
                <div class="px-3 py-2 text-sm text-amber-200">{{ Auth::user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}" class="px-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-xs bg-rose-700 hover:bg-rose-800 text-white px-3 py-1.5 rounded-lg transition">
                        Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <main class="container mx-auto px-4 py-6">
        @if(isset($header))
            <div class="mb-4">
                {{ $header }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
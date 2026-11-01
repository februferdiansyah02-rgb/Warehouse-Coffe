<!-- SIDEBAR NAVIGATION LEFT -->
<aside class="fixed inset-y-0 left-0 z-20 w-64 bg-[#12100E] border-r border-[#26211C] flex-col justify-between p-4 hidden md:flex text-[#E0D8D0] select-none">
    <div class="space-y-6">
        <!-- Logo & Branding Header -->
        <div class="flex items-center space-x-3 px-2 pt-2">
            <div class="w-10 h-10 bg-[#26211C] text-[#D99B52] border border-[#3A322B] rounded-lg flex items-center justify-center font-bold text-lg">
                K
            </div>
            <div>
                <h1 class="font-bold text-white text-lg tracking-wider leading-none">KOPI<span class="text-[#D99B52]">.ONE</span></h1>
                <span class="text-[10px] text-stone-500 uppercase tracking-widest font-semibold block mt-1">CAFE MANAGEMENT</span>
            </div>
        </div>

        <!-- Navigation Menu List -->
        <div class="space-y-1 pt-2">
            <span class="text-[10px] text-stone-500 uppercase font-semibold tracking-wider px-3 block mb-3">MENU UTAMA</span>

            <!-- 1. Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5 text-[#D99B52]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="font-semibold">Dashboard</span>
            </a>

            <!-- 2. Data Kopi -->
            <a href="{{ route('data-kopi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('data-kopi.index') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"></path>
                </svg>
                <span>Data Kopi</span>
            </a>

            <!-- 3. Kategori -->
            <a href="{{ route('kategori.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('kategori.index') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span>Kategori</span>
            </a>

            <!-- 4. Supplier -->
            <a href="{{ route('supplier.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('supplier.index') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8"></path>
                </svg>
                <span>Supplier</span>
            </a>

            <!-- 5. Barang Masuk -->
            <a href="{{ route('barang-masuk.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('barang-masuk.index') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Barang Masuk</span>
            </a>

            <!-- 6. Barang Keluar -->
            <a href="{{ route('barang-keluar.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('barang-keluar.index') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <span>Barang Keluar</span>
            </a>

            <!-- 7. Stok Gudang (dengan Badge Count) -->
            <a href="{{ route('inventory.index') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('inventory.index') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Stok Gudang</span>
                </div>
                <span class="bg-[#C85A4B] text-white text-xs font-bold px-2.5 py-0.5 rounded-full">{{ \App\Models\Item::where('stock_kg', '<', 10)->count() }}</span>
            </a>

            <!-- 8. Laporan -->
            <a href="{{ route('laporan.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('laporan.index') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Laporan</span>
            </a>

            <!-- 9. Profil Gudang -->
            <a href="{{ route('warehouse.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium transition {{ request()->routeIs('warehouse.*') ? 'bg-[#231F1B] text-white border border-[#3A322B] shadow-inner' : 'text-stone-400 hover:bg-[#1A1714] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span>Profil Gudang</span>
            </a>

        </div>
    </div>

    <!-- Bottom Section: Active Warehouse Card & Admin Profile -->
    <div class="space-y-4 pt-4 border-t border-[#26211C]">
        <!-- Card Gudang Aktif -->
        <a href="{{ route('warehouse.edit') }}" class="block bg-[#1A1714] p-3.5 rounded-2xl border border-[#26211C] flex items-center justify-between cursor-pointer hover:border-[#3A322B] transition">
            @php $wp = \App\Models\WarehouseProfile::first(); @endphp
            <div class="flex items-center space-x-3">
                @if ($wp && $wp->logo)
                    <img src="{{ asset('storage/' . $wp->logo) }}" alt="Logo" class="w-10 h-10 object-contain rounded-xl border border-[#3A322B] bg-[#26211C]">
                @else
                    <div class="w-10 h-10 bg-[#26211C] text-[#D99B52] border border-[#3A322B] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                @endif
                <div>
                    <span class="text-[10px] text-stone-500 font-medium block leading-none mb-1">Gudang Aktif</span>
                    <h4 class="text-xs font-bold text-white">{{ $wp->name ?? 'Gudang Utama' }}</h4>
                    <span class="text-[10px] text-stone-500 block">{{ $wp->address ?? 'Jakarta Selatan' }}</span>
                </div>
            </div>
            <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- User Profile Dropdown / Logout -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false" 
                    class="w-full flex items-center justify-between p-1.5 rounded-2xl hover:bg-[#1A1714] transition focus:outline-none">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#D99B52] text-[#12100E] font-bold rounded-full flex items-center justify-center text-sm overflow-hidden">
                        @if (Auth::user()->photo)
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'AR', 0, 2)) }}
                        @endif
                    </div>>
                    <div class="text-left">
                        <h4 class="text-xs font-bold text-white leading-tight">{{ Auth::user()->name ?? 'Andi Rahman' }}</h4>
                        <span class="text-[10px] text-stone-500">Administrator</span>
                    </div>
                </div>
                <svg class="w-5 h-5 text-stone-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                </svg>
            </button>

            <!-- Popup Menu Logout -->
            <div x-show="open" x-transition 
                 class="absolute bottom-14 left-0 w-full bg-[#181512] border border-[#26211C] rounded-2xl shadow-2xl p-1 z-50 overflow-hidden"
                 style="display: none;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-rose-400 hover:bg-rose-950/30 rounded-xl transition flex items-center space-x-2 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

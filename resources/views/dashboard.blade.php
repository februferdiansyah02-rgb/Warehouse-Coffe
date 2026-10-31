<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inventory - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js CDN untuk Grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#161310] text-[#E0D8D0] font-sans min-h-screen flex antialiased">

    <!-- SIDEBAR NAVIGATION LEFT -->
    <aside class="fixed inset-y-0 left-0 z-20 w-64 bg-[#12100E] border-r border-[#26211C] self-start flex flex-col justify-between p-4 hidden md:flex select-none flex-shrink-0">
        <div class="space-y-6">
            <!-- Branding Header -->
            <div class="flex items-center space-x-3 px-1 pt-2">
                <div class="w-10 h-10 bg-[#26211C] text-[#D99B52] border border-[#3A322B] rounded-lg flex items-center justify-center font-bold text-lg">
                    K
                </div>
                <div>
                    <h1 class="font-bold text-white text-lg tracking-wider leading-none">KOPI<span class="text-[#D99B52]">.ONE</span></h1>
                    <span class="text-[10px] text-stone-500 uppercase tracking-widest font-semibold block mt-1">CAFE MANAGEMENT</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-1 pt-2">
                <span class="text-[10px] text-stone-500 uppercase font-semibold tracking-wider px-3 block mb-3">MENU UTAMA</span>

                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium bg-[#231F1B] text-white border border-[#3A322B] shadow-inner transition">
                    <svg class="w-5 h-5 text-[#D99B52]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="font-semibold">Dashboard</span>
                </a>

                <a href="{{ route('data-kopi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"></path>
                    </svg>
                    <span>Data Kopi</span>
                </a>

                <a href="{{ route('kategori.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('supplier.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8"></path>
                    </svg>
                    <span>Supplier</span>
                </a>

                <a href="{{ route('barang-masuk.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Barang Masuk</span>
                </a>

                <a href="{{ route('barang-keluar.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>Barang Keluar</span>
                </a>

                <a href="{{ route('inventory.index') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>Stok Gudang</span>
                    </div>
                    <span class="bg-[#C85A4B] text-white text-xs font-bold px-2.5 py-0.5 rounded-full">
                        {{ $items->filter(fn($i) => $i->stock_kg < 10)->count() }}
                    </span>
                </a>

                <a href="{{ route('laporan.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Laporan</span>
                </a>

                <a href="{{ route('pengguna.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-medium text-stone-400 hover:bg-[#1A1714] hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Pengguna</span>
                </a>
            </div>
        </div>

        <!-- Footer Sidebar -->
        <div class="space-y-4 pt-4 border-t border-[#26211C]">
            <div class="bg-[#1A1714] p-3.5 rounded-2xl border border-[#26211C] flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#26211C] text-[#D99B52] border border-[#3A322B] rounded-xl flex items-center justify-center">
                        <i data-lucide="warehouse" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-stone-500 font-medium block leading-none mb-1">Gudang Aktif</span>
                        <h4 class="text-xs font-bold text-white">Gudang Utama</h4>
                        <span class="text-[10px] text-stone-500 block">Jakarta Selatan</span>
                    </div>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between p-1.5 rounded-2xl hover:bg-[#1A1714] transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#D99B52] text-[#12100E] font-bold rounded-full flex items-center justify-center text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'AR', 0, 2)) }}
                        </div>
                        <div class="text-left truncate">
                            <h4 class="text-xs font-bold text-white leading-tight truncate">{{ Auth::user()->name ?? 'Andi' }}</h4>
                            <span class="text-[10px] text-stone-500 block truncate">Administrator</span>
                        </div>
                    </div>
                </button>
                <div x-show="open" class="absolute bottom-14 left-0 w-full bg-[#181512] border border-[#26211C] rounded-2xl p-1 z-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-rose-400 hover:bg-rose-950/30 rounded-xl transition">Keluar (Logout)</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">

        <!-- Top Navigation Bar -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex justify-between items-center px-8">
            <!-- Search Bar -->
            <div class="relative w-96">
                <input type="text" placeholder="Cari data kopi, transaksi..." 
                       class="w-full bg-[#12100E] border border-[#26211C] text-xs text-white rounded-xl py-2.5 pl-10 pr-12 focus:outline-none focus:border-[#D99B52]">
                <svg class="w-4 h-4 text-stone-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span class="absolute right-3 top-2.5 text-[10px] bg-[#26211C] text-stone-400 px-1.5 py-0.5 rounded-md"><i data-lucide="command" class="w-4 h-4"></i> K</span>
            </div>

            <!-- Top Right Profile/Date -->
            <div class="flex items-center space-x-4">
                <button class="w-9 h-9 bg-[#12100E] border border-[#26211C] rounded-xl flex items-center justify-center text-stone-400 hover:text-white">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                <span class="text-xs text-stone-400 font-medium">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY') }}</span>
                <div class="w-8 h-8 bg-[#D99B52] text-[#12100E] font-bold rounded-full flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Body Dashboard -->
        <main class="p-8 space-y-8 max-w-7xl w-full mx-auto">

            <!-- Welcome Greeting Section -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">Selamat datang, {{ Auth::user()->name ?? 'Andi' }}</h2>
                    <p class="text-xs text-stone-400 mt-1">Pantau persediaan dan aktivitas gudang kopi hari ini.</p>
                </div>
                <div class="bg-[#181512] border border-[#26211C] px-4 py-2 rounded-xl text-xs text-stone-300 flex items-center space-x-2">
                    <span><i data-lucide="calendar" class="w-5 h-5"></i></span>
                    <span>1–{{ date('d M Y') }}</span>
                </div>
            </div>

            <!-- 4 Top Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1 -->
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C] relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 bg-[#26211C] rounded-xl flex items-center justify-center text-lg"><i data-lucide="coffee" class="w-5 h-5"></i></div>
                        <span class="text-[10px] bg-emerald-950/60 text-emerald-400 border border-emerald-800/50 px-2 py-0.5 rounded-md font-medium">^ 8.2%</span>
                    </div>
                    <span class="text-xs text-stone-400 block mb-1">Total Jenis Kopi</span>
                    <h3 class="text-2xl font-bold text-white">{{ $items->count() }} <span class="text-xs font-normal text-stone-400">produk aktif</span></h3>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 bg-[#26211C] rounded-xl flex items-center justify-center text-lg"><i data-lucide="package" class="w-5 h-5"></i></div>
                        <span class="text-[10px] bg-emerald-950/60 text-emerald-400 border border-emerald-800/50 px-2 py-0.5 rounded-md font-medium">^ 12.4%</span>
                    </div>
                    <span class="text-xs text-stone-400 block mb-1">Total Stok Gudang</span>
                    <h3 class="text-2xl font-bold text-white">{{ number_format($items->sum('stock_kg'), 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">kilogram</span></h3>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 bg-[#26211C] rounded-xl flex items-center justify-center text-lg"><i data-lucide="bar-chart-2" class="w-5 h-5"></i></div>
                        <span class="text-[10px] bg-rose-950/60 text-rose-300 border border-rose-800/50 px-2 py-0.5 rounded-md font-medium">v Perlu aksi</span>
                    </div>
                    <span class="text-xs text-stone-400 block mb-1">Stok Menipis</span>
                    <h3 class="text-2xl font-bold text-white">{{ $items->filter(fn($i) => $i->stock_kg < 10)->count() }} <span class="text-xs font-normal text-stone-400">jenis kopi</span></h3>
                </div>

                <!-- Card 4 -->
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 bg-[#26211C] rounded-xl flex items-center justify-center text-lg"><i data-lucide="trending-up" class="w-5 h-5"></i></div>
                        <span class="text-[10px] bg-emerald-950/60 text-emerald-400 border border-emerald-800/50 px-2 py-0.5 rounded-md font-medium">^ 5.7%</span>
                    </div>
                    <span class="text-xs text-stone-400 block mb-1">Stok Bersih Bulan Ini</span>
                    <h3 class="text-2xl font-bold text-white">+284 <span class="text-xs font-normal text-stone-400">kilogram</span></h3>
                </div>
            </div>

            <!-- Grid Charts (Tren & Pergerakan) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik Tren Stok (Line Chart) -->
                <div class="lg:col-span-2 bg-[#181512] p-6 rounded-2xl border border-[#26211C]">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-base font-bold text-white">Tren Stok Bulanan</h3>
                            <p class="text-xs text-stone-400">Saldo akhir stok selama 12 bulan terakhir</p>
                        </div>
                        <select class="bg-[#12100E] border border-[#26211C] text-xs text-stone-300 px-3 py-1.5 rounded-xl">
                            <option>12 Bulan</option>
                        </select>
                    </div>
                    <div class="h-64">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Pergerakan Bulan Ini (Doughnut Chart) -->
                <div class="bg-[#181512] p-6 rounded-2xl border border-[#26211C] flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-bold text-white">Pergerakan Bulan Ini</h3>
                            <a href="#" class="text-xs text-[#D99B52]">Lihat detail</a>
                        </div>
                        <p class="text-xs text-stone-400 mb-6">Masuk dibanding keluar</p>

                        <div class="relative h-44 flex items-center justify-center">
                            <canvas id="movementChart"></canvas>
                        </div>

                        <div class="space-y-3 mt-6">
                            <div class="flex justify-between text-xs">
                                <span class="text-stone-400 flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6] inline-block mr-2"></span> Barang Masuk (520 kg)</span>
                                <span class="text-white font-bold">69%</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-stone-400 flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-[#D99B52] inline-block mr-2"></span> Barang Keluar (236 kg)</span>
                                <span class="text-white font-bold">31%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Bawah: Stok Menipis & Transaksi Terbaru -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Stok Menipis (Progress Bar) -->
                <div class="bg-[#181512] p-6 rounded-2xl border border-[#26211C]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-bold text-white">Stok Menipis</h3>
                            <p class="text-xs text-stone-400">Produk mencapai batas minimum</p>
                        </div>
                        <a href="{{ route('inventory.index') }}" class="text-xs text-[#D99B52]">Lihat semua &rarr;</a>
                    </div>

                    <div class="space-y-5">
                        <!-- Item 1 -->
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-[#26211C] rounded-xl flex items-center justify-center text-sm"><i data-lucide="coffee" class="w-5 h-5"></i></div>
                            <div class="flex-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-white">Arabika Gayo</span>
                                    <span class="text-rose-400 font-bold">8 kg <span class="text-stone-500 font-normal">tersisa</span></span>
                                </div>
                                <div class="w-full bg-[#12100E] rounded-full h-2">
                                    <div class="bg-rose-500 h-2 rounded-full" style="width: 40%"></div>
                                </div>
                                <span class="text-[10px] text-stone-500">Minimum 10 kg</span>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-[#26211C] rounded-xl flex items-center justify-center text-sm"><i data-lucide="coffee" class="w-5 h-5"></i></div>
                            <div class="flex-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-white">Robusta Temanggung</span>
                                    <span class="text-rose-400 font-bold">4 kg <span class="text-stone-500 font-normal">tersisa</span></span>
                                </div>
                                <div class="w-full bg-[#12100E] rounded-full h-2">
                                    <div class="bg-rose-500 h-2 rounded-full" style="width: 25%"></div>
                                </div>
                                <span class="text-[10px] text-stone-500">Minimum 12 kg</span>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-[#26211C] rounded-xl flex items-center justify-center text-sm"><i data-lucide="coffee" class="w-5 h-5"></i></div>
                            <div class="flex-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-white">Liberika Rangsang</span>
                                    <span class="text-rose-400 font-bold">2 kg <span class="text-stone-500 font-normal">tersisa</span></span>
                                </div>
                                <div class="w-full bg-[#12100E] rounded-full h-2">
                                    <div class="bg-rose-500 h-2 rounded-full" style="width: 15%"></div>
                                </div>
                                <span class="text-[10px] text-stone-500">Minimum 8 kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Terbaru -->
                <div class="bg-[#181512] p-6 rounded-2xl border border-[#26211C]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-base font-bold text-white">Transaksi Terbaru</h3>
                            <p class="text-xs text-stone-400">Aktivitas gudang terkini</p>
                        </div>
                        <a href="#" class="text-xs text-[#D99B52]">Lihat semua &rarr;</a>
                    </div>

                    <div class="space-y-4">
                        <!-- Transaksi 1 -->
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-[#12100E] transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 bg-emerald-950/60 border border-emerald-800/50 text-emerald-400 rounded-xl flex items-center justify-center"><i data-lucide="package-plus" class="w-5 h-5"></i></div>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Barang Masuk</h4>
                                    <span class="text-[10px] text-stone-500">BM-2412-089 · PT Aroma Nusantara</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-emerald-400">+120 kg</span>
                                <span class="text-[10px] text-stone-500 block">12 Des, 10:42</span>
                            </div>
                        </div>

                        <!-- Transaksi 2 -->
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-[#12100E] transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 bg-rose-950/60 border border-rose-800/50 text-rose-400 rounded-xl flex items-center justify-center"><i data-lucide="package-minus" class="w-5 h-5"></i></div>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Barang Keluar</h4>
                                    <span class="text-[10px] text-stone-500">BK-2412-046 · Roastery Jakarta</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-rose-400">-45 kg</span>
                                <span class="text-[10px] text-stone-500 block">12 Des, 09:18</span>
                            </div>
                        </div>

                        <!-- Transaksi 3 -->
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-[#12100E] transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 bg-emerald-950/60 border border-emerald-800/50 text-emerald-400 rounded-xl flex items-center justify-center"><i data-lucide="package-plus" class="w-5 h-5"></i></div>
                                <div>
                                    <h4 class="text-xs font-bold text-white">Barang Masuk</h4>
                                    <span class="text-[10px] text-stone-500">BM-2412-088 · Koperasi Gayo</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-emerald-400">+80 kg</span>
                                <span class="text-[10px] text-stone-500 block">11 Des, 15:30</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Script Render Grafik Chart.js -->
    <script>
        // 1. Chart Line Tren Stok
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Stok (kg)',
                    data: [1100, 1000, 1250, 1180, 1450, 1380, 1600, 1520, 1650, 1580, 1720, 1842],
                    borderColor: '#D99B52',
                    backgroundColor: 'rgba(217, 155, 82, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#78716c', font: { size: 10 } } },
                    y: { grid: { color: '#26211C' }, ticks: { color: '#78716c', font: { size: 10 } } }
                }
            }
        });

        // 2. Chart Doughnut Pergerakan Bulan Ini
        const ctxMove = document.getElementById('movementChart').getContext('2d');
        new Chart(ctxMove, {
            type: 'doughnut',
            data: {
                labels: ['Barang Masuk', 'Barang Keluar'],
                datasets: [{
                    data: [520, 236],
                    backgroundColor: ['#3B82F6', '#D99B52'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>
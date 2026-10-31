<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Kopi - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">

    <x-sidebar />

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>

        <!-- Header Topbar -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex justify-between items-center px-8">
            <div class="relative w-96">
                <input type="text" placeholder="Cari kategori kopi..." 
                       class="w-full bg-[#12100E] border border-[#26211C] text-xs text-white rounded-xl py-2.5 pl-10 pr-12 focus:outline-none focus:border-[#D99B52]">
                <i data-lucide="search" class="w-4 h-4 text-stone-500 absolute left-3 top-3"></i>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-xs text-stone-400 font-medium">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY') }}</span>
                <div class="w-8 h-8 bg-[#D99B52] text-[#12100E] font-bold rounded-full flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-8 space-y-8 max-w-7xl w-full mx-auto">

            <!-- Section Title & Add Button -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">Kategori Varietal Kopi</h2>
                    <p class="text-xs text-stone-400 mt-1">Pengelompokan jenis biji kopi berdasarkan varietal dan metode pengolahan.</p>
                </div>
                <button class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>

            <!-- GRID CARDS KATEGORI KOPI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1: Arabica Single Origin -->
                <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden hover:border-[#D99B52] transition group">
                    <div class="h-44 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80" 
                             alt="Arabica" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#181512] via-transparent to-transparent"></div>
                        <span class="absolute top-3 right-3 bg-[#D99B52] text-[#12100E] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#D99B52]">
                            18 Varian Kopi
                        </span>
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#D99B52] transition">Arabica Single Origin</h3>
                        <p class="text-xs text-stone-400 leading-relaxed">Kopi spesialisasi dengan cita rasa kompleks, keasaman segar, dan aroma floral tinggi dari single estate.</p>
                        <div class="pt-3 flex justify-between items-center border-t border-[#26211C] text-xs">
                            <span class="text-stone-500">Total Stok: <strong class="text-emerald-400">850 kg</strong></span>
                            <a href="#" class="text-[#D99B52] hover:underline font-medium flex items-center space-x-1">
                                <span>Lihat item</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Robusta Premium -->
                <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden hover:border-[#D99B52] transition group">
                    <div class="h-44 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80" 
                             alt="Robusta" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#181512] via-transparent to-transparent"></div>
                        <span class="absolute top-3 right-3 bg-[#D99B52] text-[#12100E] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#D99B52]">
                            12 Varian Kopi
                        </span>
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#D99B52] transition">Robusta Fine Commercial</h3>
                        <p class="text-xs text-stone-400 leading-relaxed">Karakter bold, body tebal, rasa cokelat kacang yang kuat, dan kandungan kafein tinggi untuk es kopi susu.</p>
                        <div class="pt-3 flex justify-between items-center border-t border-[#26211C] text-xs">
                            <span class="text-stone-500">Total Stok: <strong class="text-emerald-400">620 kg</strong></span>
                            <a href="#" class="text-[#D99B52] hover:underline font-medium flex items-center space-x-1">
                                <span>Lihat item</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Specialty Fermentation (Anaerobic / Winey) -->
                <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden hover:border-[#D99B52] transition group">
                    <div class="h-44 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80" 
                             alt="Specialty Fermentation" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#181512] via-transparent to-transparent"></div>
                        <span class="absolute top-3 right-3 bg-[#D99B52] text-[#12100E] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#D99B52]">
                            9 Varian Kopi
                        </span>
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#D99B52] transition">Specialty Fermentation</h3>
                        <p class="text-xs text-stone-400 leading-relaxed">Biji kopi proses eksperimental (Anaerobic Natural, Wine Process, Honey) dengan aroma manis buah yang unik.</p>
                        <div class="pt-3 flex justify-between items-center border-t border-[#26211C] text-xs">
                            <span class="text-stone-500">Total Stok: <strong class="text-emerald-400">240 kg</strong></span>
                            <a href="#" class="text-[#D99B52] hover:underline font-medium flex items-center space-x-1">
                                <span>Lihat item</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: House Blend Espresso -->
                <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden hover:border-[#D99B52] transition group">
                    <div class="h-44 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80" 
                             alt="House Blend" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#181512] via-transparent to-transparent"></div>
                        <span class="absolute top-3 right-3 bg-[#D99B52] text-[#12100E] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#D99B52]">
                            6 Varian Kopi
                        </span>
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#D99B52] transition">House Blend Espresso</h3>
                        <p class="text-xs text-stone-400 leading-relaxed">Pencampuran khusus Arabika dan Robusta rahasia kafe untuk menghasilkan krema tebal dan keasaman seimbang.</p>
                        <div class="pt-3 flex justify-between items-center border-t border-[#26211C] text-xs">
                            <span class="text-stone-500">Total Stok: <strong class="text-emerald-400">132 kg</strong></span>
                            <a href="#" class="text-[#D99B52] hover:underline font-medium flex items-center space-x-1">
                                <span>Lihat item</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
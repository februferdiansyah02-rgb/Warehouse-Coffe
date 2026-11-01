<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">
    <x-sidebar />
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>
        <main class="p-8 max-w-6xl w-full mx-auto">
            <h1 class="text-2xl font-semibold text-white mb-2">Laporan</h1>
            <p class="text-xs text-stone-400">Rekap dan analisis aktivitas persediaan kopi di gudang.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <!-- Laporan Stok Kopi -->
                <a href="{{ route('laporan.stok') }}" class="block bg-[#181512] border border-[#26211C] rounded-2xl p-6 hover:border-[#3A322B] transition">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-white">1. Laporan Stok Kopi</h2>
                        <span class="text-[10px] bg-emerald-950/60 text-emerald-400 border border-emerald-800/50 px-2 py-0.5 rounded-md font-medium">Wajib</span>
                    </div>
                    <p class="text-xs text-stone-400">Menampilkan kondisi persediaan kopi saat ini, stok menipis, dan status stok (Aman / Stok Menipis).</p>
                    <span class="inline-block mt-4 text-xs text-[#D99B52] font-medium">Lihat laporan &rarr;</span>
                </a>

                <!-- Laporan Barang Masuk -->
                <a href="{{ route('laporan.masuk') }}" class="block bg-[#181512] border border-[#26211C] rounded-2xl p-6 hover:border-[#3A322B] transition">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-white">2. Laporan Barang Masuk</h2>
                        <span class="text-[10px] bg-emerald-950/60 text-emerald-400 border border-emerald-800/50 px-2 py-0.5 rounded-md font-medium">Wajib</span>
                    </div>
                    <p class="text-xs text-stone-400">Menampilkan seluruh riwayat penerimaan kopi dari supplier beserta total barang masuk.</p>
                    <span class="inline-block mt-4 text-xs text-[#D99B52] font-medium">Lihat laporan &rarr;</span>
                </a>

                <!-- Laporan Barang Keluar -->
                <a href="{{ route('laporan.keluar') }}" class="block bg-[#181512] border border-[#26211C] rounded-2xl p-6 hover:border-[#3A322B] transition">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-white">3. Laporan Barang Keluar</h2>
                        <span class="text-[10px] bg-rose-950/60 text-rose-300 border border-rose-800/50 px-2 py-0.5 rounded-md font-medium">Wajib</span>
                    </div>
                    <p class="text-xs text-stone-400">Menampilkan seluruh aktivitas pengeluaran kopi dari gudang dan total barang keluar.</p>
                    <span class="inline-block mt-4 text-xs text-[#D99B52] font-medium">Lihat laporan &rarr;</span>
                </a>

                <!-- Laporan Pergerakan Stok -->
                <a href="{{ route('laporan.pergerakan') }}" class="block bg-[#181512] border border-[#26211C] rounded-2xl p-6 hover:border-[#3A322B] transition">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-white">4. Laporan Pergerakan Stok</h2>
                        <span class="text-[10px] bg-emerald-950/60 text-emerald-400 border border-emerald-800/50 px-2 py-0.5 rounded-md font-medium">Wajib</span>
                    </div>
                    <p class="text-xs text-stone-400">Menampilkan riwayat perubahan stok (stok awal, masuk, keluar) dan saldo akhir tiap kopi.</p>
                    <span class="inline-block mt-4 text-xs text-[#D99B52] font-medium">Lihat laporan &rarr;</span>
                </a>

                <!-- Laporan Analisis Stok Bulanan -->
                <a href="{{ route('laporan.analisis') }}" class="block bg-[#181512] border border-[#26211C] rounded-2xl p-6 hover:border-[#3A322B] transition">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-white">5. Analisis Stok Bulanan</h2>
                        <span class="text-[10px] bg-amber-950/60 text-amber-300 border border-amber-800/50 px-2 py-0.5 rounded-md font-medium">Wajib</span>
                    </div>
                    <p class="text-xs text-stone-400">Menampilkan perkembangan stok setiap bulan dalam bentuk grafik, total masuk/keluar, dan perbandingan bulan ini.</p>
                    <span class="inline-block mt-4 text-xs text-[#D99B52] font-medium">Lihat laporan &rarr;</span>
                </a>

                <!-- Laporan Supplier -->
                <a href="{{ route('laporan.supplier') }}" class="block bg-[#181512] border border-[#26211C] rounded-2xl p-6 hover:border-[#3A322B] transition">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-bold text-white">6. Laporan Supplier</h2>
                        <span class="text-[10px] bg-stone-800 text-stone-300 border border-stone-700 px-2 py-0.5 rounded-md font-medium">Tambahan</span>
                    </div>
                    <p class="text-xs text-stone-400">Menampilkan aktivitas pemasok kopi: total transaksi dan total pasokan tiap supplier.</p>
                    <span class="inline-block mt-4 text-xs text-[#D99B52] font-medium">Lihat laporan &rarr;</span>
                </a>
            </div>
        </main>
    </div>
</body>
</html>

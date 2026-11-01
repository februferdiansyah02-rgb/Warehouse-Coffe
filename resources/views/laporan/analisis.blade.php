<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analisis Stok Bulanan - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">
    <x-sidebar />
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>
        <main class="p-8 max-w-6xl w-full mx-auto">
            <div class="flex flex-wrap justify-between items-end gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-white mb-2">Laporan Analisis Stok Bulanan</h1>
                    <p class="text-xs text-stone-400">Perkembangan stok kopi setiap bulan, total barang masuk/keluar, dan stok akhir.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Cetak PDF</button>
                </div>
            </div>

            <form method="GET" action="{{ route('laporan.analisis') }}" class="flex flex-wrap items-end gap-3 mb-6">
                <div>
                    <label class="block text-[10px] text-stone-400 uppercase font-semibold mb-1">Tanggal Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-[#12100E] border border-[#26211C] text-xs text-white px-3 py-2 rounded-xl" />
                </div>
                <div>
                    <label class="block text-[10px] text-stone-400 uppercase font-semibold mb-1">Tanggal Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-[#12100E] border border-[#26211C] text-xs text-white px-3 py-2 rounded-xl" />
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Filter</button>
            </form>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Barang Masuk</span>
                    <h3 class="text-2xl font-bold text-emerald-400">{{ number_format($totalMasuk, 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">kg</span></h3>
                </div>
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Barang Keluar</span>
                    <h3 class="text-2xl font-bold text-rose-400">{{ number_format($totalKeluar, 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">kg</span></h3>
                </div>
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Stok Akhir</span>
                    <h3 class="text-2xl font-bold text-white">{{ number_format($stokAkhir, 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">kg</span></h3>
                </div>
            </div>

            <!-- Grafik -->
            <div class="bg-[#181512] p-6 rounded-2xl border border-[#26211C] mb-6">
                <h3 class="text-base font-bold text-white mb-4">Grafik Pergerakan Stok</h3>
                <div class="h-72">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </main>

        <script>
            const ctxStock = document.getElementById('stockChart').getContext('2d');
            new Chart(ctxStock, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Barang Masuk (kg)',
                            data: @json($masukData),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Barang Keluar (kg)',
                            data: @json($keluarData),
                            borderColor: '#D99B52',
                            backgroundColor: 'rgba(217, 155, 82, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Stok Akhir (kg)',
                            data: @json($stokData),
                            borderColor: '#E0D8D0',
                            backgroundColor: 'rgba(224, 216, 208, 0.05)',
                            fill: true,
                            tension: 0.4
                        },
                    ],
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
        </script>
    </div>
</body>
</html>

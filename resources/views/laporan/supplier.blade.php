<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Supplier - KOPI.ONE</title>
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
            <div class="flex flex-wrap justify-between items-end gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-white mb-2">Laporan Supplier</h1>
                    <p class="text-xs text-stone-400">Aktivitas pemasok kopi: total transaksi dan total pasokan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Cetak PDF</button>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Supplier</span>
                    <h3 class="text-2xl font-bold text-white">{{ $suppliers->count() }}</h3>
                </div>
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Pasokan</span>
                    <h3 class="text-2xl font-bold text-emerald-400">{{ number_format($suppliers->sum('total_kg'), 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">kg</span></h3>
                </div>
            </div>

            <!-- Tabel Supplier -->
            <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[10px] uppercase text-stone-400 border-b border-[#26211C]">
                            <th class="px-6 py-4 font-semibold">Supplier</th>
                            <th class="px-6 py-4 font-semibold text-right">Total Transaksi</th>
                            <th class="px-6 py-4 font-semibold text-right">Total Pasokan (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr class="border-b border-[#26211C] last:border-0">
                            <td class="px-6 py-4 font-semibold text-white">{{ $supplier['name'] }}</td>
                            <td class="px-6 py-4 text-right text-white">{{ $supplier['transactions'] }}</td>
                            <td class="px-6 py-4 text-right text-emerald-400 font-semibold">{{ number_format($supplier['total_kg'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-xs text-stone-500">Tidak ada data supplier.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

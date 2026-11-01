<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Kopi - KOPI.ONE</title>
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
                    <h1 class="text-2xl font-semibold text-white mb-2">Laporan Stok Kopi</h1>
                    <p class="text-xs text-stone-400">Kondisi persediaan kopi saat ini, stok menipis, dan status stok.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Cetak PDF</button>
                    <button type="submit" name="export" value="1" form="laporan-stok-form" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#D99B52] text-[#12100E] hover:bg-[#C08548] transition">Export Excel</button>
                </div>
            </div>

            <form id="laporan-stok-form" method="GET" action="{{ route('laporan.stok') }}" class="flex flex-wrap items-end gap-3 mb-6">
                <div>
                    <label class="block text-[10px] text-stone-400 uppercase font-semibold mb-1">Kategori</label>
                    <select name="category_id" class="bg-[#12100E] border border-[#26211C] text-xs text-stone-300 px-3 py-2 rounded-xl">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] text-stone-400 uppercase font-semibold mb-1">Cari Kopi</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama kopi..." class="bg-[#12100E] border border-[#26211C] text-xs text-white px-3 py-2 rounded-xl w-64" />
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Filter</button>
            </form>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Jenis Kopi</span>
                    <h3 class="text-2xl font-bold text-white">{{ $summary['total_kopi'] }}</h3>
                </div>
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Stok Gudang</span>
                    <h3 class="text-2xl font-bold text-white">{{ number_format($summary['total_stok'], 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">kg</span></h3>
                </div>
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Stok Menipis</span>
                    <h3 class="text-2xl font-bold text-rose-400">{{ $summary['total_menipis'] }} <span class="text-xs font-normal text-stone-400">jenis kopi</span></h3>
                </div>
            </div>

            <!-- Tabel Stok -->
            <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[10px] uppercase text-stone-400 border-b border-[#26211C]">
                            <th class="px-6 py-4 font-semibold">Nama Kopi</th>
                            <th class="px-6 py-4 font-semibold">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-right">Stok Tersedia (kg)</th>
                            <th class="px-6 py-4 font-semibold text-right">Stok Minimum (kg)</th>
                            <th class="px-6 py-4 font-semibold">Status Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr class="border-b border-[#26211C] last:border-0">
                            <td class="px-6 py-4 font-semibold text-white">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 text-stone-300">{{ optional($item->category)->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right text-white">{{ number_format((float) $item->stock_kg, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-stone-300">{{ number_format((float) $item->minimum_stock, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ (float) $item->stock_kg < (float) $item->minimum_stock ? 'bg-rose-950/60 text-rose-400 border border-rose-800/50' : 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/50' }}">
                                    {{ (float) $item->stock_kg < (float) $item->minimum_stock ? 'Stok Menipis' : 'Aman' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-xs text-stone-500">Tidak ada data stok kopi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

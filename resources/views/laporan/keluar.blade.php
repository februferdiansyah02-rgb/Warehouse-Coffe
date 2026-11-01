<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Keluar - KOPI.ONE</title>
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
                    <h1 class="text-2xl font-semibold text-white mb-2">Laporan Barang Keluar</h1>
                    <p class="text-xs text-stone-400">Seluruh aktivitas pengeluaran kopi dari gudang.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Cetak PDF</button>
                    <button type="submit" name="export" value="1" form="laporan-keluar-form" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#D99B52] text-[#12100E] hover:bg-[#C08548] transition">Export Excel</button>
                </div>
            </div>

            <form id="laporan-keluar-form" method="GET" action="{{ route('laporan.keluar') }}" class="flex flex-wrap items-end gap-3 mb-6">
                <div>
                    <label class="block text-[10px] text-stone-400 uppercase font-semibold mb-1">Tanggal Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-[#12100E] border border-[#26211C] text-xs text-white px-3 py-2 rounded-xl" />
                </div>
                <div>
                    <label class="block text-[10px] text-stone-400 uppercase font-semibold mb-1">Tanggal Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-[#12100E] border border-[#26211C] text-xs text-white px-3 py-2 rounded-xl" />
                </div>
                <div>
                    <label class="block text-[10px] text-stone-400 uppercase font-semibold mb-1">Tujuan</label>
                    <select name="purpose" class="bg-[#12100E] border border-[#26211C] text-xs text-stone-300 px-3 py-2 rounded-xl">
                        <option value="">Semua Tujuan</option>
                        @foreach($purposes as $purpose => $label)
                            <option value="{{ $purpose }}" @selected(request('purpose') == $purpose)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Filter</button>
            </form>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Transaksi Keluar</span>
                    <h3 class="text-2xl font-bold text-white">{{ $transactions->count() }}</h3>
                </div>
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-400 block mb-1">Total Barang Keluar</span>
                    <h3 class="text-2xl font-bold text-rose-400">{{ number_format($totalKg, 0, ',', '.') }} <span class="text-xs font-normal text-stone-400">kg</span></h3>
                </div>
            </div>

            <!-- Tabel Barang Keluar -->
            <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[10px] uppercase text-stone-400 border-b border-[#26211C]">
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Kode Transaksi</th>
                            <th class="px-6 py-4 font-semibold">Tujuan</th>
                            <th class="px-6 py-4 font-semibold">Nama Kopi</th>
                            <th class="px-6 py-4 font-semibold text-right">Jumlah (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr class="border-b border-[#26211C] last:border-0">
                            <td class="px-6 py-4 text-stone-300">{{ optional($trx->date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $trx->code }}</td>
                            <td class="px-6 py-4 text-stone-300">{{ $trx->purpose }}</td>
                            <td class="px-6 py-4 text-stone-300">{{ $trx->items->pluck('name')->implode(', ') }}</td>
                            <td class="px-6 py-4 text-right text-rose-400 font-semibold">{{ number_format((float) $trx->total_kg, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-xs text-stone-500">Tidak ada data barang keluar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

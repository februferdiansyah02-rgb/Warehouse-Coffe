<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pergerakan Stok - KOPI.ONE</title>
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
                    <h1 class="text-2xl font-semibold text-white mb-2">Laporan Pergerakan Stok</h1>
                    <p class="text-xs text-stone-400">Riwayat perubahan stok (masuk, keluar) dan saldo akhir tiap kopi.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-[#26211C] text-[#E0D8D0] border border-[#3A322B] hover:bg-[#1A1714] transition">Cetak PDF</button>
                </div>
            </div>

            <form method="GET" action="{{ route('laporan.pergerakan') }}" class="flex flex-wrap items-end gap-3 mb-6">
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

            <!-- Tabel Pergerakan -->
            <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[10px] uppercase text-stone-400 border-b border-[#26211C]">
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Nama Kopi</th>
                            <th class="px-6 py-4 font-semibold">Jenis Transaksi</th>
                            <th class="px-6 py-4 font-semibold text-right">Masuk (kg)</th>
                            <th class="px-6 py-4 font-semibold text-right">Keluar (kg)</th>
                            <th class="px-6 py-4 font-semibold text-right">Saldo (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                        <tr class="border-b border-[#26211C] last:border-0">
                            <td class="px-6 py-4 text-stone-300">{{ $row['date'] }}</td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $row['name'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $row['type'] === 'masuk' ? 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/50' : 'bg-rose-950/60 text-rose-400 border border-rose-800/50' }}">
                                    {{ $row['type'] === 'masuk' ? 'Barang Masuk' : 'Barang Keluar' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-emerald-400 font-semibold">{{ number_format((float) $row['masuk'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-rose-400 font-semibold">{{ number_format((float) $row['keluar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-white font-semibold">{{ number_format((float) $row['saldo'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-xs text-stone-500">Tidak ada data pergerakan stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

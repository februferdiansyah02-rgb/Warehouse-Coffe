<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Inventory - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">

    <!-- SIDEBAR (component) -->
    <x-sidebar />

    <!-- MAIN CONTENT AREA KANAN -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">

        <!-- Header Mobile Only -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>

        <!-- Main Body Content -->
        <main class="p-8 max-w-6xl w-full mx-auto">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-sm rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Widget Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-500 uppercase font-bold tracking-wider block mb-1">TOTAL STOK</span>
                    <span class="text-2xl font-bold text-white">
                        {{ number_format($items->sum('stock_kg'), 2) }} <span class="text-xs font-normal text-stone-400">Kg</span>
                    </span>
                </div>

                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-500 uppercase font-bold tracking-wider block mb-1">TOTAL VARIETAL</span>
                    <span class="text-2xl font-bold text-white">
                        {{ $items->count() }} <span class="text-xs font-normal text-stone-400">Jenis</span>
                    </span>
                </div>

                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-500 uppercase font-bold tracking-wider block mb-1">ESTIMASI NILAI BARANG</span>
                    <span class="text-2xl font-bold text-[#D99B52]">
                        Rp {{ number_format($items->sum(fn($i) => $i->stock_kg * $i->price_per_kg), 0, ',', '.') }}
                    </span>
                </div>

                <div class="bg-[#181512] p-5 rounded-2xl border border-[#26211C]">
                    <span class="text-[10px] text-stone-500 uppercase font-bold tracking-wider block mb-1">STOK MENIPIS (&lt;10KG)</span>
                    <span class="text-2xl font-bold text-rose-400">
                        {{ $items->filter(fn($i) => $i->stock_kg < 10)->count() }} <span class="text-xs font-normal text-stone-400">Barang</span>
                    </span>
                </div>
            </div>

            <!-- Section Header & Add Button -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-white">Daftar Stok Kopi</h2>
                    <p class="text-xs text-stone-400 mt-1">Pantau ketersediaan varietal dan persediaan biji kopi di gudang.</p>
                </div>
                <a href="{{ route('inventory.create') }}" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition">
                    + Tambah Stok Kopi
                </a>
            </div>

            <!-- Inventory Data Table -->
            <div class="bg-[#181512] rounded-2xl border border-[#26211C] overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#1D1916] text-[#D99B52] text-xs uppercase tracking-wider border-b border-[#26211C]">
                        <tr>
                            <th class="p-4">Nama Barang</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Pemasok</th>
                            <th class="p-4">Stok (Kg)</th>
                            <th class="p-4">Harga / Kg</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#26211C] text-sm text-stone-300">
                        @forelse($items as $item)
                        <tr class="hover:bg-[#1f1b18] transition">
                            <td class="p-4 font-medium text-white">{{ $item->item_name }}</td>
                            <td class="p-4">
                                <span class="bg-[#26211C] text-[#D99B52] border border-[#3A322B] px-2.5 py-1 rounded-md text-xs">
                                    {{ $item->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-stone-400">{{ $item->supplier->name ?? '-' }}</td>
                            <td class="p-4 font-bold text-emerald-400">{{ number_format($item->stock_kg, 2) }} kg</td>
                            <td class="p-4">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus stok ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-stone-500 text-xs">
                                Belum ada data stok kopi. Silakan klik tombol <span class="text-[#D99B52]">+ Tambah Stok Kopi</span>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>
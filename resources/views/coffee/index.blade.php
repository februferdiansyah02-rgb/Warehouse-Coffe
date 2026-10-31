<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kopi - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">
    <x-sidebar />
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>
        <main class="p-8 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-sm rounded-xl">{{ session('success') }}</div>
            @endif
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-white">Data Kopi</h1>
                    <p class="text-xs text-stone-400 mt-1">Kelola data master kopi gudang.</p>
                </div>
                <a href="{{ route('data-kopi.create') }}" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition">+ Tambah Data Kopi</a>
            </div>
            <div class="bg-[#181512] rounded-2xl border border-[#26211C] overflow-x-auto shadow-xl">
                <table class="min-w-full text-left border-collapse text-[11px]">
                    <thead class="bg-[#1D1916] text-[#D99B52] uppercase tracking-wider border-b border-[#26211C] sticky top-0">
                        <tr>
                            <th class="px-3 py-2.5 whitespace-nowrap">Kode Kopi</th>
                            <th class="px-3 py-2.5 whitespace-nowrap">Nama Kopi</th>
                            <th class="px-3 py-2.5 whitespace-nowrap">Kategori</th>
                            <th class="px-3 py-2.5 whitespace-nowrap">Asal Daerah</th>
                            <th class="px-3 py-2.5 whitespace-nowrap">Jenis Proses</th>
                            <th class="px-3 py-2.5 whitespace-nowrap">Bentuk Kopi</th>
                            <th class="px-3 py-2.5 whitespace-nowrap">Grade</th>
                            <th class="px-3 py-2.5 whitespace-nowrap text-right">Stok</th>
                            <th class="px-3 py-2.5 whitespace-nowrap">Satuan</th>
                            <th class="px-3 py-2.5 whitespace-nowrap text-right">Harga Beli</th>
                            <th class="px-3 py-2.5 whitespace-nowrap text-center">Status</th>
                            <th class="px-3 py-2.5 whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#26211C] text-stone-300">
                        @forelse($coffees as $coffee)
                        <tr class="hover:bg-[#1f1b18] transition">
                            <td class="px-3 py-2.5 font-semibold text-white whitespace-nowrap">{{ $coffee->code }}</td>
                            <td class="px-3 py-2.5 font-medium text-white whitespace-nowrap">{{ $coffee->name }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap">{{ $coffee->category->name ?? '-' }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap">{{ $coffee->origin ?? '-' }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap">{{ $coffee->process ?? '-' }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap">{{ $coffee->form ?? '-' }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap">{{ $coffee->grade ?? '-' }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap text-right font-bold text-emerald-400">{{ number_format($coffee->stock, 2) }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap">{{ $coffee->unit }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap text-right">Rp {{ number_format($coffee->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium {{ $coffee->status === 'Aktif' ? 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/50' : 'bg-stone-800 text-stone-400 border border-stone-700' }}">{{ $coffee->status }}</span>
                            </td>
                            <td class="px-3 py-2.5 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('data-kopi.edit', $coffee) }}" class="text-[#D99B52] hover:text-[#c28742] font-medium">Edit</a>
                                    <form action="{{ route('data-kopi.destroy', $coffee) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data kopi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="p-10 text-center text-stone-500 text-xs">Belum ada data kopi. Klik "+ Tambah Data Kopi" untuk menambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
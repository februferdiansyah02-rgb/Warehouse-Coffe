<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barang Masuk - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js untuk Form Transaksi Terpadu & Kalkulasi Otomatis -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased" x-data='barangMasukPage({
    transactions: @json($transactions, JSON_HEX_APOS),
    coffees: @json($coffees, JSON_HEX_APOS),
    categories: @json($categories, JSON_HEX_APOS),
    suppliers: @json($suppliers, JSON_HEX_APOS),
    toast: null,
    toastType: "success"
})'>

    <!-- SIDEBAR COMPONENT (KONSISTEN TIDAK BERUBAH) -->
    <x-sidebar />

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">

        <!-- Top Navigation Bar -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex justify-between items-center px-8">
            <!-- Fitur Pencarian Kode Transaksi / Supplier -->
            <div class="relative w-96">
                <input type="text" x-model="searchQuery" placeholder="Cari kode transaksi, supplier, faktur..." 
                       class="w-full bg-[#12100E] border border-[#26211C] text-xs text-white rounded-xl py-2.5 pl-10 pr-12 focus:outline-none focus:border-[#D99B52]">
                <i data-lucide="search" class="w-4 h-4 text-stone-500 absolute left-3 top-3"></i>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-xs text-stone-400 font-medium">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY') }}</span>
                <div class="w-8 h-8 bg-[#D99B52] text-[#12100E] font-bold rounded-full flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'F', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="p-8 space-y-8 max-w-7xl w-full mx-auto">

            <!-- Section Header & Controls -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Transaksi Barang Masuk</h2>
                    <p class="text-xs text-stone-400 mt-1">Pencatatan penerimaan stok biji kopi dari supplier. Stok bertambah secara otomatis.</p>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Filter Tanggal -->
                    <input type="date" x-model="dateFilter" class="bg-[#181512] border border-[#26211C] text-xs text-stone-300 px-3 py-2.5 rounded-xl focus:outline-none focus:border-[#D99B52]">

                    <!-- Tombol Tambah Barang Masuk -->
                    <button @click="openFormModal()" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2">
                        <i data-lucide="package-plus" class="w-4 h-4"></i>
                        <span>+ Tambah Barang Masuk</span>
                    </button>
                </div>
            </div>

            <!-- TABEL DAFTAR TRANSAKSI BARANG MASUK -->
            <div class="bg-[#181512] rounded-2xl border border-[#26211C] overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#1D1916] text-[#D99B52] text-xs uppercase tracking-wider border-b border-[#26211C]">
                        <tr>
                            <th class="p-4">Kode Transaksi</th>
                            <th class="p-4">Tanggal & Faktur</th>
                            <th class="p-4">Supplier</th>
                            <th class="p-4">Total Masuk (Kg)</th>
                            <th class="p-4">Total Pembelian</th>
                            <th class="p-4">Petugas</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#26211C] text-sm text-stone-300">
                        <template x-for="trx in filteredTransactions" :key="trx.code">
                            <tr class="hover:bg-[#1f1b18] transition">
                                <td class="p-4 font-mono text-xs font-bold text-white" x-text="trx.code"></td>
                                <td class="p-4">
                                    <span class="block text-white text-xs font-medium" x-text="trx.date"></span>
                                    <span class="text-[10px] text-stone-500 font-mono" x-text="trx.invoice"></span>
                                </td>
                                <td class="p-4 text-stone-300 font-medium" x-text="trx.supplier"></td>
                                <td class="p-4 font-bold text-emerald-400" x-text="trx.totalKg + ' kg'"></td>
                                <td class="p-4 font-bold text-[#D99B52]" x-text="'Rp ' + formatNumber(trx.totalPrice)"></td>
                                <td class="p-4 text-stone-400 text-xs" x-text="trx.officer"></td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <!-- Detail Transaksi -->
                                        <button @click="openDetailModal(trx)" class="text-stone-400 hover:text-white" title="Lihat Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <!-- Cetak Bukti -->
                                        <button @click="printReceipt(trx)" class="text-[#D99B52] hover:text-white" title="Cetak Bukti Nota">
                                            <i data-lucide="printer" class="w-4 h-4"></i>
                                        </button>
                                        <!-- Batalkan Transaksi -->
                                        <button @click="cancelTransaction(trx)" class="text-rose-400 hover:text-rose-300" title="Batalkan Transaksi">
                                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredTransactions.length === 0">
                            <td colspan="7" class="p-8 text-center text-stone-500 text-xs">
                                Tidak ada data transaksi barang masuk yang ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div class="fixed bottom-5 right-5 z-[60] flex flex-col gap-2 pointer-events-none">
        <template x-for="t in toast ? [toast] : []" :key="t.id">
            <div x-show="toast" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0 opacity-100"
                 x-transition:leave-end="translate-y-full opacity-0"
                 class="pointer-events-auto flex w-full max-w-sm items-center gap-3 px-4 py-3 rounded-2xl border text-sm font-medium shadow-xl animate-in slide-in-from-bottom-4"
                 :class="toastType === 'success' ? 'bg-emerald-500/90 text-white border-emerald-400/50' : 'bg-rose-500/90 text-white border-rose-400/50'">
                <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"
                   :class="toastType === 'success' ? 'text-emerald-200' : 'text-rose-200'"></i>
                <span x-text="toast.message"></span>
                <button @click="toast = null" class="ml-auto text-xs opacity-70 hover:opacity-100">&times;</button>
            </div>
        </template>
    </div>

    <!-- MODAL FORM TRANSAKSI TERPADU -->
    <div x-show="showFormModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/75 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-[#181512] border border-[#26211C] rounded-2xl w-full max-w-3xl p-6 space-y-6 shadow-2xl max-h-[90vh] overflow-y-auto"
             x-show="showFormModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="flex justify-between items-center border-b border-[#26211C] pb-3">
                <div>
                    <h3 class="font-bold text-white text-base">Input Transaksi Barang Masuk</h3>
                    <p class="text-[11px] text-stone-500">Lengkapi data penerimaan dan detail kopi yang dikirim supplier.</p>
                </div>
                <button @click="showFormModal = false" class="text-stone-500 hover:text-white text-lg">&times;</button>
            </div>

            <form @submit.prevent="saveTransaction" class="space-y-6">
                <!-- 1. INFORMASI TRANSAKSI & SUPPLIER (KOLOM SUPPLIER MENGGUNAKAN INPUT TEKS) -->
                <div class="bg-[#12100E] p-4 rounded-xl border border-[#26211C] grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Kode Transaksi</label>
                        <input type="text" x-model="formData.code" readonly 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-[#D99B52] font-mono font-bold focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Tanggal Masuk (Otomatis)</label>
                        <input type="date" x-model="formData.date" readonly 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-stone-400 cursor-not-allowed focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Supplier / Pemasok</label>
                        <select x-model="formData.supplier_id" @change="onSupplierChange()" required
                                class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                            <option value="">Pilih supplier...</option>
                            <template x-for="s in suppliers" :key="s.id">
                                <option :value="s.id" x-text="s.name"></option>
                            </template>
                            <option value="manual">Input manual</option>
                        </select>
                        <input x-show="formData.supplier_id === 'manual'" x-model="formData.supplier" type="text" placeholder="Ketik nama supplier..." 
                               class="mt-2 w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white placeholder-stone-600 focus:outline-none focus:border-[#D99B52]">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Nomor Faktur / Invoice</label>
                        <input type="text" x-model="formData.invoice" placeholder="INV-001" required 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Petugas Penerima</label>
                        <input type="text" x-model="formData.officer" required 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Catatan Pengiriman</label>
                        <input type="text" x-model="formData.note" placeholder="Pengiriman tahap pertama" 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                </div>

                <!-- 2. DETAIL KOPI YANG MASUK (TABEL DINAMIS) -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Detail Item Kopi Diterima</h4>
                        <button type="button" @click="addCoffeeRow()" class="text-xs text-[#D99B52] hover:underline flex items-center space-x-1">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                            <span>+ Tambah Baris Kopi</span>
                        </button>
                    </div>

                    <div class="border border-[#26211C] rounded-xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#12100E] text-stone-400 border-b border-[#26211C]">
                                <tr>
                                    <th class="p-3">Nama & Jenis Kopi</th>
                                    <th class="p-3 w-28">Jumlah (Kg)</th>
                                    <th class="p-3 w-36">Harga Beli / Kg</th>
                                    <th class="p-3 w-36">Subtotal</th>
                                    <th class="p-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#26211C]">
                                <template x-for="(row, index) in formData.items" :key="index">
                                    <tr class="bg-[#181512]">
                                        <td class="p-2 space-y-2">
                                            <select x-model="row.coffee_id" @change="onCoffeeChange(row)"
                                                    class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white text-xs focus:outline-none focus:border-[#D99B52]">
                                                <option value="">Pilih kopi (data kopi)...</option>
                                                <template x-for="c in coffees" :key="c.id">
                                                    <option :value="c.id" x-text="c.code + ' - ' + c.name + ' (' + ((c.category && c.category.name) ? c.category.name : 'Tanpa Kategori') + ')'"></option>
                                                </template>
                                                <option value="manual">Input manual (kopi baru)</option>
                                            </select>
                                            <div x-show="row.coffee_id === 'manual' || row.coffee_id === ''" class="space-y-2">
                                                <input type="text" x-model="row.name" :required="row.coffee_id === 'manual' || row.coffee_id === ''" placeholder="Nama kopi baru"
                                                       class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white placeholder-stone-600 text-xs focus:outline-none focus:border-[#D99B52]">
                                                <select x-model="row.category_id" @change="onExistingCoffeeCategoryChange(row)" :required="row.coffee_id === 'manual' || row.coffee_id === ''"
                                                        class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white text-xs focus:outline-none focus:border-[#D99B52]">
                                                    <option value="">Pilih kategori...</option>
                                                    <template x-for="cat in categories" :key="cat.id">
                                                        <option :value="cat.id" x-text="cat.name"></option>
                                                    </template>
                                                    <option value="new">+ Input Kategori Baru</option>
                                                </select>
                                                <input x-show="row.category_id === 'new'" x-model="row.category_name" type="text" placeholder="Ketik nama kategori baru..."
                                                       class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white placeholder-stone-600 text-xs focus:outline-none focus:border-[#D99B52]">
                                            </div>
                                            <div x-show="row.coffee_id !== '' && row.coffee_id !== 'manual'" class="space-y-1">
                                                <div class="text-[10px] text-stone-400 flex items-center justify-between">
                                                    <span>Kategori: <strong class="text-[#D99B52]" x-text="row.category_name || '(Belum ada kategori)'"></strong></span>
                                                    <button type="button" @click="row.edit_category = !row.edit_category" class="text-[10px] text-stone-400 hover:text-white underline ml-2" x-text="row.edit_category ? 'Tutup' : 'Ubah'"></button>
                                                </div>
                                                <div x-show="row.edit_category || !row.category_id" class="space-y-1 pt-1">
                                                    <select x-model="row.category_id" @change="onExistingCoffeeCategoryChange(row)"
                                                            class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white text-xs focus:outline-none focus:border-[#D99B52]">
                                                        <option value="">Pilih kategori...</option>
                                                        <template x-for="cat in categories" :key="cat.id">
                                                            <option :value="cat.id" x-text="cat.name"></option>
                                                        </template>
                                                        <option value="new">+ Input Kategori Baru</option>
                                                    </select>
                                                    <input x-show="row.category_id === 'new'" x-model="row.category_name" type="text" placeholder="Ketik nama kategori baru..."
                                                           class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white placeholder-stone-600 text-xs focus:outline-none focus:border-[#D99B52]">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <input type="number" min="1" x-model.number="row.qty" required placeholder="100" 
                                                   class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white focus:outline-none focus:border-[#D99B52]">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" min="0" step="500" x-model.number="row.price" required placeholder="85000" 
                                                   class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white focus:outline-none focus:border-[#D99B52]">
                                        </td>
                                        <td class="p-2 font-bold text-stone-200" x-text="'Rp ' + formatNumber(row.qty * row.price)"></td>
                                        <td class="p-2 text-center">
                                            <button type="button" @click="removeCoffeeRow(index)" x-show="formData.items.length > 1" class="text-rose-400 hover:text-rose-300">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. RINGKASAN TOTAL -->
                <div class="bg-[#12100E] p-4 rounded-xl border border-[#26211C] flex justify-between items-center text-xs">
                    <div>
                        <span class="text-stone-500 block">Total Jumlah Kopi:</span>
                        <span class="text-lg font-bold text-emerald-400" x-text="calculateTotalKg() + ' Kg'"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-stone-500 block">Total Pembelian:</span>
                        <span class="text-xl font-bold text-[#D99B52]" x-text="'Rp ' + formatNumber(calculateTotalPrice())"></span>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" @click="showFormModal = false" class="px-4 py-2 text-xs text-stone-400 hover:text-white">Batal</button>
                    <button type="submit" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-bold text-xs px-5 py-2.5 rounded-xl shadow transition">
                        Simpan Transaksi & Tambah Stok
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL TRANSAKSI -->
    <div x-show="showDetailModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/75 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-[#181512] border border-[#26211C] rounded-2xl w-full max-w-xl p-6 space-y-4 shadow-2xl"
             x-show="showDetailModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex justify-between items-center border-b border-[#26211C] pb-3">
                <h3 class="font-bold text-white text-base">Rincian Transaksi Barang Masuk</h3>
                <button @click="showDetailModal = false" class="text-stone-500 hover:text-white">&times;</button>
            </div>

            <div class="space-y-2 text-xs">
                <div class="grid grid-cols-2 gap-2 bg-[#12100E] p-3 rounded-xl border border-[#26211C]">
                    <div><span class="text-stone-500 block">Kode Transaksi:</span><strong class="text-white font-mono" x-text="selectedTrx.code"></strong></div>
                    <div><span class="text-stone-500 block">Tanggal Penerimaan:</span><strong class="text-white" x-text="selectedTrx.date"></strong></div>
                    <div><span class="text-stone-500 block">Supplier:</span><strong class="text-white" x-text="selectedTrx.supplier"></strong></div>
                    <div><span class="text-stone-500 block">Nomor Faktur:</span><strong class="text-white font-mono" x-text="selectedTrx.invoice"></strong></div>
                    <div><span class="text-stone-500 block">Petugas Penerima:</span><strong class="text-white" x-text="selectedTrx.officer"></strong></div>
                    <div><span class="text-stone-500 block">Catatan:</span><strong class="text-stone-300" x-text="selectedTrx.note || '-'"></strong></div>
                </div>

                <div class="border border-[#26211C] rounded-xl overflow-hidden mt-3">
                    <table class="w-full text-left">
                        <thead class="bg-[#12100E] text-stone-400 border-b border-[#26211C]">
                            <tr>
                                <th class="p-2.5">Nama Kopi</th>
                                <th class="p-2.5 text-right">Jumlah</th>
                                <th class="p-2.5 text-right">Harga/Kg</th>
                                <th class="p-2.5 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#26211C] text-stone-300">
                            <template x-for="item in selectedTrx.items" :key="item.name">
                                <tr>
                                    <td class="p-2.5 font-medium text-white" x-text="item.name"></td>
                                    <td class="p-2.5 text-right font-bold text-emerald-400" x-text="item.qty + ' kg'"></td>
                                    <td class="p-2.5 text-right" x-text="'Rp ' + formatNumber(item.price)"></td>
                                    <td class="p-2.5 text-right font-bold text-[#D99B52]" x-text="'Rp ' + formatNumber(item.qty * item.price)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center pt-2 font-bold text-sm">
                    <span class="text-stone-400">Total Pembelian:</span>
                    <span class="text-[#D99B52]" x-text="'Rp ' + formatNumber(selectedTrx.totalPrice)"></span>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button @click="showDetailModal = false" class="bg-[#26211C] text-stone-300 hover:text-white text-xs px-4 py-2 rounded-xl border border-[#3A322B]">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script Alpine.js -->
    <script>
        function barangMasukPage(initial) {
            initial = initial || {};
            return {
                searchQuery: '',
                dateFilter: '',
                showFormModal: false,
                showDetailModal: false,
                selectedTrx: {},
                coffees: initial.coffees || [],
                categories: initial.categories || [],
                suppliers: initial.suppliers || [],
                formData: {
                    code: 'BM-' + new Date().getFullYear() + '-' + String((initial.transactions || []).length + 1).padStart(3, '0'),
                    date: new Date().toISOString().split('T')[0],
                    supplier_id: (initial.suppliers && initial.suppliers.length) ? initial.suppliers[0].id : '',
                    supplier: (initial.suppliers && initial.suppliers.length) ? initial.suppliers[0].name : '',
                    invoice: 'INV-' + new Date().toISOString().slice(0,10).replace(/-/g,''),
                    officer: '{{ Auth::user()->name ?? "Ferdi" }}',
                    note: '',
                    items: (initial.coffees && initial.coffees.length) ? [
                        {
                            coffee_id: initial.coffees[0].id,
                            name: initial.coffees[0].name,
                            category_id: initial.coffees[0].category_id,
                            category_name: (initial.coffees[0].category && initial.coffees[0].category.name) ? initial.coffees[0].category.name : '',
                            qty: 1,
                            price: Number(initial.coffees[0].purchase_price || 0)
                        }
                    ] : [
                        { coffee_id: '', name: '', category_id: (initial.categories && initial.categories.length) ? initial.categories[0].id : '', category_name: (initial.categories && initial.categories.length) ? initial.categories[0].name : '', qty: 1, price: 0 }
                    ]
                },
                transactions: initial.transactions || [],
                get filteredTransactions() {
                    return this.transactions.filter(trx => {
                        const matchesSearch = trx.code.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                              (trx.supplier || '').toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                              (trx.invoice || '').toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesDate = !this.dateFilter || trx.date === this.dateFilter;
                        return matchesSearch && matchesDate;
                    });
                },
                openFormModal() {
                    this.formData.code = 'BM-' + new Date().getFullYear() + '-' + String(this.transactions.length + 1).padStart(3, '0');
                    this.formData.date = new Date().toISOString().split('T')[0];
                    this.formData.supplier_id = (this.suppliers && this.suppliers.length) ? this.suppliers[0].id : '';
                    this.formData.supplier = (this.suppliers && this.suppliers.length) ? this.suppliers[0].name : '';
                    this.formData.invoice = 'INV-' + new Date().toISOString().slice(0,10).replace(/-/g,'');
                    this.formData.note = '';
                    this.formData.items = (this.coffees && this.coffees.length) ? [
                        {
                            coffee_id: this.coffees[0].id,
                            name: this.coffees[0].name,
                            category_id: this.coffees[0].category_id,
                            category_name: (this.coffees[0].category && this.coffees[0].category.name) ? this.coffees[0].category.name : '',
                            qty: 1,
                            price: Number(this.coffees[0].purchase_price || 0)
                        }
                    ] : [
                        { coffee_id: '', name: '', category_id: (this.categories && this.categories.length) ? this.categories[0].id : '', category_name: (this.categories && this.categories.length) ? this.categories[0].name : '', qty: 1, price: 0 }
                    ];
                    this.showFormModal = true;
                },
                openDetailModal(trx) {
                    this.selectedTrx = trx;
                    this.showDetailModal = true;
                },
                onCoffeeChange(row) {
                    if (row.coffee_id === 'manual' || row.coffee_id === '') {
                        if (row.coffee_id === '') {
                            row.name = '';
                            row.category_id = (this.categories && this.categories.length) ? this.categories[0].id : '';
                            row.category_name = (this.categories && this.categories.length) ? this.categories[0].name : '';
                            row.price = 0;
                            row.edit_category = false;
                        } else {
                            row.edit_category = true;
                        }
                    } else {
                        const c = this.coffees.find(x => String(x.id) === String(row.coffee_id));
                        if (c) {
                            row.name = c.name;
                            row.category_id = c.category_id || '';
                            row.category_name = (c.category && c.category.name) ? c.category.name : '';
                            row.price = Number(c.purchase_price || 0);
                            row.edit_category = !c.category_id;
                        }
                    }
                },
                onExistingCoffeeCategoryChange(row) {
                    if (row.category_id !== 'new') {
                        const cat = this.categories.find(x => String(x.id) === String(row.category_id));
                        row.category_name = cat ? cat.name : '';
                    } else {
                        row.category_name = '';
                    }
                },
                onSupplierChange() {
                    if (this.formData.supplier_id !== 'manual') {
                        const s = this.suppliers.find(x => String(x.id) === String(this.formData.supplier_id));
                        this.formData.supplier = s ? s.name : '';
                    } else {
                        this.formData.supplier = '';
                    }
                },
                addCoffeeRow() {
                    this.formData.items.push({
                        coffee_id: '',
                        name: '',
                        category_id: (this.categories && this.categories.length) ? this.categories[0].id : '',
                        category_name: (this.categories && this.categories.length) ? this.categories[0].name : '',
                        qty: 1,
                        price: 0,
                        edit_category: false
                    });
                },
                removeCoffeeRow(index) {
                    if (this.formData.items.length > 1) {
                        this.formData.items.splice(index, 1);
                    }
                },
                calculateTotalKg() {
                    return this.formData.items.reduce((sum, i) => sum + (Number(i.qty) || 0), 0);
                },
                calculateTotalPrice() {
                    return this.formData.items.reduce((sum, i) => sum + ((Number(i.qty) || 0) * (Number(i.price) || 0)), 0);
                },
                async saveTransaction() {
                    const items = this.formData.items.map(row => {
                        const manual = row.coffee_id === 'manual' || row.coffee_id === '';
                        const isNewCat = row.category_id === 'new';
                        return {
                            coffee_id: (!manual && row.coffee_id) ? row.coffee_id : null,
                            name: row.name,
                            category_id: (!isNewCat && row.category_id) ? row.category_id : null,
                            category_name: isNewCat ? row.category_name : (row.category_name || null),
                            qty: Number(row.qty) || 0,
                            price: Number(row.price) || 0,
                        };
                    });

                    const supplierId = this.formData.supplier_id && this.formData.supplier_id !== 'manual'
                        ? this.formData.supplier_id : null;

                    const payload = {
                        code: this.formData.code,
                        date: this.formData.date,
                        supplier_id: supplierId,
                        supplier: this.formData.supplier,
                        invoice: this.formData.invoice,
                        officer: this.formData.officer,
                        note: this.formData.note,
                        items: items,
                    };

                    try {
                        const res = await fetch('{{ route('barang-masuk.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload),
                        });

                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            this.showToast('Gagal menyimpan: ' + (err.message || res.statusText), 'error');
                            return;
                        }

                        const data = await res.json();
                        this.transactions.unshift(data.transaction);
                        if (data.coffees) this.coffees = data.coffees;
                        if (data.categories) this.categories = data.categories;
                        this.showFormModal = false;
                        this.showToast(data.message, 'success');
                    } catch (e) {
                        this.showToast('Terjadi kesalahan: ' + e.message, 'error');
                    }
                },
                async cancelTransaction(trx) {
                    if (!confirm('Batalkan transaksi ' + trx.code + '?')) return;
                    try {
                        const res = await fetch('/barang-masuk/' + trx.id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
                        if (res.ok) {
                            window.location.reload();
                        } else {
                            this.showToast('Gagal membatalkan transaksi.', 'error');
                        }
                    } catch (e) {
                        this.showToast('Terjadi kesalahan: ' + e.message, 'error');
                    }
                },
                printReceipt(trx) {
                    this.showToast('Mencetak bukti penerimaan barang masuk: ' + trx.code, 'success');
                },
                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num || 0);
                },
                showToast(message, type = "success") {
                    this.toast = { id: Date.now(), message: message };
                    this.toastType = type;
                    setTimeout(() => {
                        this.toast = null;
                    }, 4000);
                }
            }
        }
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>

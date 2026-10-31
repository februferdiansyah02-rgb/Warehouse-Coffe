<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barang Keluar - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js untuk Form Transaksi Barang Keluar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased" x-data='barangKeluarPage({
    transactions: @json($transactions, JSON_HEX_APOS),
    coffees: @json($coffees, JSON_HEX_APOS),
    categories: @json($categories, JSON_HEX_APOS),
    purposes: @json($purposes, JSON_HEX_APOS),
    toast: null,
    toastType: "success"
})'>

    <!-- SIDEBAR COMPONENT -->
    <x-sidebar />

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">

        <!-- Top Navigation Bar -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex justify-between items-center px-8">
            <!-- Fitur Pencarian / Filter -->
            <div class="relative w-96">
                <input type="text" x-model="searchQuery" placeholder="Cari kode, penerima, referensi..." 
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
                    <h2 class="text-2xl font-bold text-white">Transaksi Barang Keluar</h2>
                    <p class="text-xs text-stone-400 mt-1">Pencatatan pengeluaran stok kopi (penjualan, produksi, pengiriman, dan lain-lain). Stok akan dikurangi otomatis.</p>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Filter Tujuan -->
                    <select x-model="purposeFilter" class="bg-[#181512] border border-[#26211C] text-xs text-stone-300 px-3 py-2.5 rounded-xl focus:outline-none focus:border-[#D99B52]">
                        <option value="">Semua Tujuan</option>
                        <template x-for="(label, value) in purposes" :key="value">
                            <option :value="value" x-text="label"></option>
                        </template>
                    </select>

                    <!-- Filter Tanggal -->
                    <input type="date" x-model="dateFilter" class="bg-[#181512] border border-[#26211C] text-xs text-stone-300 px-3 py-2.5 rounded-xl focus:outline-none focus:border-[#D99B52]">

                    <!-- Tombol Tambah Barang Keluar -->
                    <button @click="openFormModal()" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2">
                        <i data-lucide="package-minus" class="w-4 h-4"></i>
                        <span>+ Barang Keluar</span>
                    </button>
                </div>
            </div>

            <!-- TABEL DAFTAR TRANSAKSI BARANG KELUAR -->
            <div class="bg-[#181512] rounded-2xl border border-[#26211C] overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#1D1916] text-[#D99B52] text-xs uppercase tracking-wider border-b border-[#26211C]">
                        <tr>
                            <th class="p-4">Kode Transaksi</th>
                            <th class="p-4">Tanggal & Tujuan</th>
                            <th class="p-4">Penerima & Referensi</th>
                            <th class="p-4">Total Keluar (Kg)</th>
                            <th class="p-4">Total Nilai</th>
                            <th class="p-4">Petugas</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#26211C] text-sm text-stone-300">
                        <template x-for="trx in filteredTransactions" :key="trx.code">
                            <tr class="hover:bg-[#1f1b18] transition">
                                <td class="p-4 font-mono text-xs font-bold text-white" x-text="trx.code"></td>
                                <td class="p-4">
                                    <span class="block text-white text-xs font-medium" x-text="trx.date_formatted"></span>
                                    <span class="text-[10px] text-stone-500" x-text="trx.purpose"></span>
                                </td>
                                <td class="p-4">
                                    <span class="block text-stone-300 font-medium" x-text="trx.recipient_name"></span>
                                    <span class="text-[10px] text-stone-500 font-mono" x-text="trx.reference_no || '-'"></span>
                                </td>
                                <td class="p-4 font-bold text-rose-400" x-text="trx.totalKg + ' kg'"></td>
                                <td class="p-4 font-bold text-[#D99B52]" x-text="'Rp ' + formatNumber(trx.totalPrice)"></td>
                                <td class="p-4 text-stone-400 text-xs" x-text="trx.officer"></td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <!-- Detail Transaksi -->
                                        <button @click="openDetailModal(trx)" class="text-stone-400 hover:text-white" title="Lihat Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <!-- Edit Transaksi -->
                                        <button @click="openEditModal(trx)" class="text-[#D99B52] hover:text-white" title="Edit Transaksi">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
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
                                Tidak ada data transaksi barang keluar yang ditemukan.
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

    <!-- MODAL FORM TRANSAKSI BARANG KELUAR -->
    <div x-show="showFormModal || showEditModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/75 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-[#181512] border border-[#26211C] rounded-2xl w-full max-w-3xl p-6 space-y-6 shadow-2xl max-h-[90vh] overflow-y-auto"
             x-show="showFormModal || showEditModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <div class="flex justify-between items-center border-b border-[#26211C] pb-3">
                <div>
                    <h3 class="font-bold text-white text-base" x-text="isEditMode ? 'Edit Transaksi Barang Keluar' : 'Input Transaksi Barang Keluar'"></h3>
                    <p class="text-[11px] text-stone-500" x-text="isEditMode ? 'Perbaiki data pengeluaran kopi' : 'Lengkapi data pengeluaran dan detail kopi yang dikeluarkan.'"></p>
                </div>
                <button @click="closeFormModal()" class="text-stone-500 hover:text-white text-lg">&times;</button>
            </div>

            <form @submit.prevent="saveTransaction" class="space-y-6">
                <!-- 1. INFORMASI TRANSAKSI -->
                <div class="bg-[#12100E] p-4 rounded-xl border border-[#26211C] grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Kode Transaksi</label>
                        <input type="text" x-model="formData.code" readonly 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-[#D99B52] font-mono font-bold focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Tanggal Keluar</label>
                        <input type="date" x-model="formData.date" required 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Tujuan Keluar</label>
                        <select x-model="formData.purpose" required
                                class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                            <option value="">Pilih tujuan...</option>
                            <template x-for="(label, value) in purposes" :key="value">
                                <option :value="value" x-text="label"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Nama Penerima</label>
                        <input type="text" x-model="formData.recipient_name" placeholder="Kedai Kopi ABC" required 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">No. Referensi / PO</label>
                        <input type="text" x-model="formData.reference_no" placeholder="PO-001" 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Petugas Pengeluaran</label>
                        <input type="text" x-model="formData.officer" required 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                    <div>
                        <label class="block text-[11px] text-stone-400 mb-1 font-medium">Catatan</label>
                        <input type="text" x-model="formData.note" placeholder="Pengiriman pesanan" 
                               class="w-full bg-[#181512] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                    </div>
                </div>

                <!-- 2. DETAIL KOPI YANG KELUAR (TABEL DINAMIS) -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Detail Item Kopi Keluar</h4>
                        <button type="button" @click="addItemRow()" class="text-xs text-[#D99B52] hover:underline flex items-center space-x-1">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                            <span>+ Tambah Baris Kopi</span>
                        </button>
                    </div>

                    <div class="border border-[#26211C] rounded-xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#12100E] text-stone-400 border-b border-[#26211C]">
                                <tr>
                                    <th class="p-3">Nama Kopi</th>
                                    <th class="p-3 w-24 text-center">Stok Tersedia</th>
                                    <th class="p-3 w-24 text-center">Jumlah Keluar</th>
                                    <th class="p-3 w-20 text-center">Satuan</th>
                                    <th class="p-3 w-32 text-right">Harga / Kg</th>
                                    <th class="p-3 w-32 text-right">Subtotal</th>
                                    <th class="p-3 w-10 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#26211C]">
                                <template x-for="(row, index) in formData.items" :key="index">
                                    <tr class="bg-[#181512]">
                                        <td class="p-2 space-y-1">
                                            <select x-model="row.coffee_id" @change="onCoffeeChange(row, index)"
                                                    class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white text-xs focus:outline-none focus:border-[#D99B52]">
                                                <option value="">Pilih kopi...</option>
                                                <template x-for="c in coffees" :key="c.id">
                                                    <option :value="c.id" x-text="c.code + ' - ' + c.name + ' (' + ((c.category && c.category.name) ? c.category.name : '-') + ')'"></option>
                                                </template>
                                                <option value="manual">Input manual (kopi baru)</option>
                                            </select>
                                            <div x-show="row.coffee_id === 'manual' || row.coffee_id === ''" class="space-y-1">
                                                <input type="text" x-model="row.name" :required="row.coffee_id === 'manual' || row.coffee_id === ''" placeholder="Nama kopi"
                                                       class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white placeholder-stone-600 text-xs focus:outline-none focus:border-[#D99B52]">
                                                <select x-model="row.category_id" :required="row.coffee_id === 'manual' || row.coffee_id === ''"
                                                        class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white text-xs focus:outline-none focus:border-[#D99B52]">
                                                    <option value="">Pilih kategori...</option>
                                                    <template x-for="cat in categories" :key="cat.id">
                                                        <option :value="cat.id" x-text="cat.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div x-show="row.coffee_id !== '' && row.coffee_id !== 'manual'" class="text-[10px] text-stone-500">
                                                Kategori: <span class="text-[#D99B52]" x-text="row.category_name || '-'"></span>
                                            </div>
                                        </td>
                                        <td class="p-2 text-center">
                                            <span class="text-[10px] text-stone-400 font-mono" x-text="row.available_stock ? row.available_stock + ' kg' : '-'"></span>
                                            <input type="hidden" x-model="row.available_stock">
                                        </td>
                                        <td class="p-2">
                                            <input type="number" min="0.01" step="0.01" x-model.number="row.qty" required placeholder="30" 
                                                   class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white focus:outline-none focus:border-[#D99B52]">
                                        </td>
                                        <td class="p-2 text-center">
                                            <input type="text" x-model="row.unit" value="Kg" readonly 
                                                   class="w-full bg-[#181512] border border-[#26211C] rounded-lg p-2 text-white text-xs focus:outline-none">
                                        </td>
                                        <td class="p-2 text-right">
                                            <input type="number" min="0" step="100" x-model.number="row.price" required placeholder="100000" 
                                                   class="w-full bg-[#12100E] border border-[#26211C] rounded-lg p-2 text-white text-right focus:outline-none focus:border-[#D99B52]">
                                        </td>
                                        <td class="p-2 text-right font-bold text-stone-200" x-text="'Rp ' + formatNumber(row.qty * row.price)"></td>
                                        <td class="p-2 text-center">
                                            <button type="button" @click="removeItemRow(index)" x-show="formData.items.length > 1" class="text-rose-400 hover:text-rose-300">
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
                        <span class="text-stone-500 block">Total Jumlah Keluar:</span>
                        <span class="text-lg font-bold text-rose-400" x-text="calculateTotalKg() + ' Kg'"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-stone-500 block">Total Nilai Transaksi:</span>
                        <span class="text-xl font-bold text-[#D99B52]" x-text="'Rp ' + calculateTotalPrice()"></span>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" @click="closeFormModal()" class="px-4 py-2 text-xs text-stone-400 hover:text-white">Batal</button>
                    <button type="submit" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-bold text-xs px-5 py-2.5 rounded-xl shadow transition">
                        <span x-text="isEditMode ? 'Perbarui Transaksi' : 'Simpan Transaksi & Kurangi Stok'"></span>
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
                <h3 class="font-bold text-white text-base">Rincian Transaksi Barang Keluar</h3>
                <button @click="showDetailModal = false" class="text-stone-500 hover:text-white">&times;</button>
            </div>

            <div class="space-y-2 text-xs">
                <div class="grid grid-cols-2 gap-2 bg-[#12100E] p-3 rounded-xl border border-[#26211C]">
                    <div><span class="text-stone-500 block">Kode Transaksi:</span><strong class="text-white font-mono" x-text="selectedTrx.code"></strong></div>
                    <div><span class="text-stone-500 block">Tanggal Keluar:</span><strong class="text-white" x-text="selectedTrx.date_formatted"></strong></div>
                    <div><span class="text-stone-500 block">Tujuan Keluar:</span><strong class="text-[#D99B52]" x-text="selectedTrx.purpose"></strong></div>
                    <div><span class="text-stone-500 block">Nama Penerima:</span><strong class="text-white" x-text="selectedTrx.recipient_name"></strong></div>
                    <div><span class="text-stone-500 block">No. Referensi:</span><strong class="text-white font-mono" x-text="selectedTrx.reference_no || '-'"></strong></div>
                    <div><span class="text-stone-500 block">Petugas Pengeluaran:</span><strong class="text-white" x-text="selectedTrx.officer"></strong></div>
                    <div><span class="text-stone-500 block">Catatan:</span><strong class="text-stone-300" x-text="selectedTrx.note || '-'"></strong></div>
                </div>

                <div class="border border-[#26211C] rounded-xl overflow-hidden mt-3">
                    <table class="w-full text-left">
                        <thead class="bg-[#12100E] text-stone-400 border-b border-[#26211C]">
                            <tr>
                                <th class="p-2.5">Nama Kopi</th>
                                <th class="p-2.5 text-right">Stok Awal</th>
                                <th class="p-2.5 text-right">Keluar</th>
                                <th class="p-2.5 text-right">Satuan</th>
                                <th class="p-2.5 text-right">Harga</th>
                                <th class="p-2.5 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#26211C] text-stone-300">
                            <template x-for="item in selectedTrx.items" :key="item.name">
                                <tr>
                                    <td class="p-2.5 font-medium text-white" x-text="item.name"></td>
                                    <td class="p-2.5 text-right font-bold text-emerald-400" x-text="item.available_stock + ' kg'"></td>
                                    <td class="p-2.5 text-right font-bold text-rose-400" x-text="item.qty + ' kg'"></td>
                                    <td class="p-2.5 text-right" x-text="item.unit"></td>
                                    <td class="p-2.5 text-right" x-text="'Rp ' + formatNumber(item.price)"></td>
                                    <td class="p-2.5 text-right font-bold text-[#D99B52]" x-text="'Rp ' + formatNumber(item.subtotal)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center pt-2 font-bold text-sm border-t border-[#26211C]">
                    <span class="text-stone-400">Total Barang Keluar:</span>
                    <span class="text-[#D99B52]" x-text="selectedTrx.totalKg + ' kg'"></span>
                </div>
                <div class="flex justify-between items-center pt-1 font-bold text-sm">
                    <span class="text-stone-400">Total Nilai:</span>
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
        function barangKeluarPage(initial) {
            initial = initial || {};
            const now = new Date();
            return {
                searchQuery: '',
                purposeFilter: '',
                dateFilter: '',
                showFormModal: false,
                showEditModal: false,
                showDetailModal: false,
                isEditMode: false,
                editingId: null,
                selectedTrx: {},
                coffees: initial.coffees || [],
                categories: initial.categories || [],
                purposes: initial.purposes || {},
                formData: {
                    code: 'BK-' + now.getFullYear() + '-' + String((initial.transactions || []).length + 1).padStart(3, '0'),
                    date: now.toISOString().split('T')[0],
                    purpose: 'Penjualan',
                    recipient_name: '',
                    reference_no: '',
                    officer: '{{ Auth::user()->name ?? "Ferdi" }}',
                    note: '',
                    items: (initial.coffees && initial.coffees.length) ? [
                        {
                            coffee_id: initial.coffees[0].id,
                            name: initial.coffees[0].name,
                            category_id: initial.coffees[0].category_id,
                            category_name: (initial.coffees[0].category && initial.coffees[0].category.name) ? initial.coffees[0].category.name : '',
                            available_stock: Number(initial.coffees[0].stock || 0),
                            qty: 1,
                            unit: 'Kg',
                            price: 0,
                            subtotal: 0
                        }
                    ] : [
                        { coffee_id: '', name: '', category_id: '', category_name: '', available_stock: 0, qty: 1, unit: 'Kg', price: 0, subtotal: 0 }
                    ]
                },
                transactions: initial.transactions || [],
                toast: null,
                toastType: 'success',
                get filteredTransactions() {
                    return this.transactions.filter(trx => {
                        const matchesSearch = trx.code.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                              (trx.recipient_name || '').toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                              (trx.reference_no || '').toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                              (trx.purpose || '').toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesPurpose = !this.purposeFilter || trx.purpose === this.purposeFilter;
                        const matchesDate = !this.dateFilter || trx.date === this.dateFilter;
                        return matchesSearch && matchesPurpose && matchesDate;
                    });
                },
                openFormModal() {
                    this.isEditMode = false;
                    this.editingId = null;
                    this.showDetailModal = false;
                    this.formData.code = 'BK-' + now.getFullYear() + '-' + String(this.transactions.length + 1).padStart(3, '0');
                    this.formData.date = now.toISOString().split('T')[0];
                    this.formData.purpose = 'Penjualan';
                    this.formData.recipient_name = '';
                    this.formData.reference_no = '';
                    this.formData.note = '';
                    this.formData.officer = '{{ Auth::user()->name ?? "Ferdi" }}';
                    this.formData.items = (this.coffees && this.coffees.length) ? [
                        {
                            coffee_id: this.coffees[0].id,
                            name: this.coffees[0].name,
                            category_id: this.coffees[0].category_id,
                            category_name: (this.coffees[0].category && this.coffees[0].category.name) ? this.coffees[0].category.name : '',
                            available_stock: Number(this.coffees[0].stock || 0),
                            qty: 1,
                            unit: 'Kg',
                            price: 0,
                            subtotal: 0
                        }
                    ] : [
                        { coffee_id: '', name: '', category_id: '', category_name: '', available_stock: 0, qty: 1, unit: 'Kg', price: 0, subtotal: 0 }
                    ];
                    this.showFormModal = true;
                },
                openEditModal(trx) {
                    this.isEditMode = true;
                    this.editingId = trx.id;
                    this.showDetailModal = false;
                    this.formData.code = trx.code;
                    this.formData.date = trx.date;
                    this.formData.purpose = trx.purpose;
                    this.formData.recipient_name = trx.recipient_name;
                    this.formData.reference_no = trx.reference_no || '';
                    this.formData.officer = trx.officer;
                    this.formData.note = trx.note || '';
                    this.formData.items = trx.items.map(item => ({
                        id: item.id,
                        coffee_id: item.coffee_id || '',
                        name: item.name,
                        category_id: item.category_id || '',
                        category_name: item.category || '',
                        available_stock: item.available_stock,
                        qty: item.qty,
                        unit: item.unit || 'Kg',
                        price: item.price,
                        subtotal: item.subtotal
                    }));
                    this.showEditModal = true;
                },
                openDetailModal(trx) {
                    this.selectedTrx = trx;
                    this.showDetailModal = true;
                },
                closeFormModal() {
                    this.showFormModal = false;
                    this.showEditModal = false;
                    this.isEditMode = false;
                    this.editingId = null;
                },
                onCoffeeChange(row, index) {
                    if (row.coffee_id === 'manual' || row.coffee_id === '') {
                        if (row.coffee_id === '') {
                            row.name = '';
                            row.category_id = '';
                            row.category_name = '';
                            row.available_stock = 0;
                            row.price = 0;
                        }
                    } else {
                        const c = this.coffees.find(x => String(x.id) === String(row.coffee_id));
                        if (c) {
                            row.name = c.name;
                            row.category_id = c.category_id;
                            row.category_name = (c.category && c.category.name) ? c.category.name : '';
                            row.available_stock = Number(c.stock || 0);
                            row.price = Number(c.purchase_price || 0);
                        }
                    }
                    this.calculateSubtotal(index);
                },
                addItemRow() {
                    this.formData.items.push({ coffee_id: '', name: '', category_id: '', category_name: '', available_stock: 0, qty: 1, unit: 'Kg', price: 0, subtotal: 0 });
                },
                removeItemRow(index) {
                    if (this.formData.items.length > 1) {
                        this.formData.items.splice(index, 1);
                    }
                },
                calculateSubtotal(index) {
                    const row = this.formData.items[index];
                    row.subtotal = (Number(row.qty) || 0) * (Number(row.price) || 0);
                },
                calculateTotalKg() {
                    return this.formData.items.reduce((sum, i) => sum + (Number(i.qty) || 0), 0);
                },
                calculateTotalPrice() {
                    return this.formData.items.reduce((sum, i) => sum + ((Number(i.qty) || 0) * (Number(i.price) || 0)), 0);
                },
                async saveTransaction() {
                    // Hitung subtotal setiap baris sebelum kirim
                    this.formData.items = this.formData.items.map((row, index) => {
                        row.subtotal = (Number(row.qty) || 0) * (Number(row.price) || 0);
                        return row;
                    });

                    // Validasi: stok mencukupi
                    for (const row of this.formData.items) {
                        if (Number(row.qty) > Number(row.available_stock)) {
                            this.showToast('Stok tidak mencukupi untuk ' + row.name + ' (tersedia: ' + row.available_stock + ' kg)', 'error');
                            return;
                        }
                    }

                    const items = this.formData.items.map(row => {
                        const manual = row.coffee_id === 'manual' || row.coffee_id === '';
                        return {
                            coffee_id: (!manual && row.coffee_id) ? row.coffee_id : null,
                            category_id: manual ? (row.category_id || null) : (row.category_id || null),
                            name: row.name,
                            available_stock: Number(row.available_stock) || 0,
                            qty: Number(row.qty) || 0,
                            unit: row.unit || 'Kg',
                            price: Number(row.price) || 0,
                            subtotal: Number(row.subtotal) || 0,
                        };
                    });

                    const payload = {
                        code: this.formData.code,
                        date: this.formData.date,
                        purpose: this.formData.purpose,
                        recipient_name: this.formData.recipient_name,
                        reference_no: this.formData.reference_no,
                        officer: this.formData.officer,
                        note: this.formData.note,
                        items: items,
                    };

                    const url = this.isEditMode
                        ? '{{ route('barang-keluar.update', 0) }}'.replace('/0', '/' + this.editingId)
                        : '{{ route('barang-keluar.store') }}';
                    const method = this.isEditMode ? 'PUT' : 'POST';

                    try {
                        const res = await fetch(url, {
                            method: method,
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
                        if (this.isEditMode) {
                            const idx = this.transactions.findIndex(t => t.id === data.transaction.id);
                            if (idx !== -1) this.transactions[idx] = data.transaction;
                        } else {
                            this.transactions.unshift(data.transaction);
                        }
                        this.closeFormModal();
                        this.showToast(data.message, 'success');
                    } catch (e) {
                        this.showToast('Terjadi kesalahan: ' + e.message, 'error');
                    }
                },
                async cancelTransaction(trx) {
                    if (!confirm('Batalkan transaksi ' + trx.code + '? Stok kopi akan dikembalikan.')) return;
                    try {
                        const res = await fetch('/barang-keluar/' + trx.id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
                        if (res.ok) {
                            this.transactions = this.transactions.filter(t => t.id !== trx.id);
                            this.showToast('Transaksi dibatalkan dan stok telah dikembalikan.', 'success');
                        } else {
                            this.showToast('Gagal membatalkan transaksi.', 'error');
                        }
                    } catch (e) {
                        this.showToast('Terjadi kesalahan: ' + e.message, 'error');
                    }
                },
                printReceipt(trx) {
                    this.showToast('Mencetak bukti pengeluaran barang keluar: ' + trx.code, 'success');
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

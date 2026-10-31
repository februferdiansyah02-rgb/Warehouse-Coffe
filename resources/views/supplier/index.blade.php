<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Supplier - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js untuk Modal & Filter Interaktif -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased">

    <x-sidebar />

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0" x-data="supplierPage()">

        <!-- Header Mobile Only -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>

        <!-- Top Navigation Bar -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex justify-between items-center px-8">
            <!-- 6. Fitur Pencarian Kode / Nama -->
            <div class="relative w-96">
                <input type="text" x-model="searchQuery" placeholder="Cari nama atau kode supplier..." 
                       class="w-full bg-[#12100E] border border-[#26211C] text-xs text-white rounded-xl py-2.5 pl-10 pr-12 focus:outline-none focus:border-[#D99B52]">
                <i data-lucide="search" class="w-4 h-4 text-stone-500 absolute left-3 top-3"></i>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-xs text-stone-400 font-medium">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY') }}</span>
                <div class="w-8 h-8 bg-[#D99B52] text-[#12100E] font-bold rounded-full flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="p-8 space-y-8 max-w-7xl w-full mx-auto">

            <!-- Section Header & Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Daftar Supplier (Pemasok)</h2>
                    <p class="text-xs text-stone-400 mt-1">Kelola data mitra koperasi petani dan distributor bahan baku kopi.</p>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- 7. Filter Status (Aktif / Nonaktif / Semua) -->
                    <select x-model="statusFilter" class="bg-[#181512] border border-[#26211C] text-xs text-stone-300 px-3 py-2.5 rounded-xl focus:outline-none focus:border-[#D99B52]">
                        <option value="all">Semua Status</option>
                        <option value="aktif">Status: Aktif</option>
                        <option value="nonaktif">Status: Nonaktif</option>
                    </select>

                    <!-- 2. Tambah Supplier Button -->
                    <button @click="openFormModal('create')" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>+ Tambah Supplier</span>
                    </button>
                </div>
            </div>

            <!-- 1. TABEL DAFTAR SUPPLIER -->
            <div class="bg-[#181512] rounded-2xl border border-[#26211C] overflow-hidden shadow-xl">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#1D1916] text-[#D99B52] text-xs uppercase tracking-wider border-b border-[#26211C]">
                        <tr>
                            <th class="p-4">Kode</th>
                            <th class="p-4">Nama Supplier</th>
                            <th class="p-4">Kontak / HP</th>
                            <th class="p-4">Kota asal</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#26211C] text-sm text-stone-300">
                        <template x-for="item in filteredSuppliers" :key="item.id">
                            <tr class="hover:bg-[#1f1b18] transition">
                                <td class="p-4 font-mono text-xs text-stone-400" x-text="item.code"></td>
                                <td class="p-4 font-bold text-white" x-text="item.name"></td>
                                <td class="p-4 text-stone-300" x-text="item.phone"></td>
                                <td class="p-4 text-stone-400" x-text="item.city"></td>
                                <td class="p-4">
                                    <span :class="item.status === 'aktif' ? 'bg-emerald-950/60 text-emerald-400 border-emerald-800/50' : 'bg-rose-950/60 text-rose-400 border-rose-800/50'"
                                          class="border px-2.5 py-1 rounded-md text-[10px] uppercase font-bold tracking-wider"
                                          x-text="item.status"></span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <!-- 4. Detail Supplier Button -->
                                        <button @click="openDetailModal(item)" class="text-stone-400 hover:text-white" title="Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <!-- 3. Edit Supplier Button -->
                                        <button @click="openFormModal('edit', item)" class="text-[#D99B52] hover:text-white" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <!-- 5. Hapus Supplier Button -->
                                        <button @click="deleteSupplier(item.id)" class="text-rose-400 hover:text-rose-300" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredSuppliers.length === 0">
                            <td colspan="6" class="p-8 text-center text-stone-500 text-xs">
                                Tidak ada data supplier yang sesuai dengan pencarian / filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>

    <!-- MODAL FORM (TAMBAH & EDIT SUPPLIER) -->
    <div x-show="showFormModal" class="fixed inset-0 md:left-64 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-[#181512] border border-[#26211C] rounded-2xl w-full max-w-md p-6 space-y-4 shadow-2xl">
            <div class="flex justify-between items-center border-b border-[#26211C] pb-3">
                <h3 class="font-bold text-white text-base" x-text="modalMode === 'create' ? 'Tambah Supplier Baru' : 'Edit Data Supplier'"></h3>
                <button @click="showFormModal = false" class="text-stone-500 hover:text-white">&times;</button>
            </div>

            <form @submit.prevent="saveSupplier" class="space-y-3">
                <div>
                    <label class="block text-xs text-stone-400 mb-1">Kode Supplier</label>
                    <input type="text" x-model="formData.code" required placeholder="SUP-001" 
                           class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                </div>
                <div>
                    <label class="block text-xs text-stone-400 mb-1">Nama Supplier / Koperasi</label>
                    <input type="text" x-model="formData.name" required placeholder="PT Aroma Nusantara" 
                           class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                </div>
                <div>
                    <label class="block text-xs text-stone-400 mb-1">Kontak / Telepon (WA)</label>
                    <input type="text" x-model="formData.phone" required placeholder="081234567890" 
                           class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                </div>
                <div>
                    <label class="block text-xs text-stone-400 mb-1">Kota Asal</label>
                    <input type="text" x-model="formData.city" required placeholder="Takengon, Aceh" 
                           class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                </div>
                <div>
                    <label class="block text-xs text-stone-400 mb-1">Status Kemitraan</label>
                    <select x-model="formData.status" class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-[#26211C]">
                    <button type="button" @click="showFormModal = false" class="px-4 py-2 text-xs text-stone-400 hover:text-white">Batal</button>
                    <button type="submit" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2 rounded-xl">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL SUPPLIER -->
    <div x-show="showDetailModal" class="fixed inset-0 md:left-64 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;">
        <div class="bg-[#181512] border border-[#26211C] rounded-2xl w-full max-w-md p-6 space-y-4 shadow-2xl">
            <div class="flex justify-between items-center border-b border-[#26211C] pb-3">
                <h3 class="font-bold text-white text-base">Detail Informasi Supplier</h3>
                <button @click="showDetailModal = false" class="text-stone-500 hover:text-white">&times;</button>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-[#26211C]">
                    <span class="text-stone-500">Kode Supplier:</span>
                    <span class="text-white font-mono font-bold" x-text="selectedDetail.code"></span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#26211C]">
                    <span class="text-stone-500">Nama Supplier:</span>
                    <span class="text-white font-bold" x-text="selectedDetail.name"></span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#26211C]">
                    <span class="text-stone-500">Kontak / WA:</span>
                    <span class="text-[#D99B52] font-semibold" x-text="selectedDetail.phone"></span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#26211C]">
                    <span class="text-stone-500">Kota / Lokasi:</span>
                    <span class="text-stone-300" x-text="selectedDetail.city"></span>
                </div>
                <div class="flex justify-between py-1 border-b border-[#26211C]">
                    <span class="text-stone-500">Status Kemitraan:</span>
                    <span class="uppercase font-bold text-emerald-400" x-text="selectedDetail.status"></span>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button @click="showDetailModal = false" class="bg-[#26211C] text-stone-300 hover:text-white text-xs px-4 py-2 rounded-xl border border-[#3A322B]">Tutup</button>
            </div>
        </div>
    </div>

    </div>

    <!-- Script Alpine.js Logika CRUD & Search Dummy/State -->
    <script>
        function supplierPage() {
            return {
                searchQuery: '',
                statusFilter: 'all',
                showFormModal: false,
                showDetailModal: false,
                modalMode: 'create', // create | edit
                formData: { id: null, code: '', name: '', phone: '', city: '', status: 'aktif' },
                selectedDetail: {},
                suppliers: [
                    { id: 1, code: 'SUP-001', name: 'Koperasi Tani Gayo High', phone: '0812-8899-7711', city: 'Takengon, Aceh', status: 'aktif' },
                    { id: 2, code: 'SUP-002', name: 'PT Aroma Nusantara', phone: '0813-1122-3344', city: 'Bandung, Jabar', status: 'aktif' },
                    { id: 3, code: 'SUP-003', name: 'Roastery Jakarta', phone: '0857-4433-2211', city: 'Jakarta Selatan', status: 'nonaktif' },
                ],
                get filteredSuppliers() {
                    return this.suppliers.filter(item => {
                        const matchesSearch = item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                              item.code.toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesStatus = this.statusFilter === 'all' || item.status === this.statusFilter;
                        return matchesSearch && matchesStatus;
                    });
                },
                openFormModal(mode, item = null) {
                    this.modalMode = mode;
                    if (mode === 'edit' && item) {
                        this.formData = { ...item };
                    } else {
                        this.formData = { id: Date.now(), code: '', name: '', phone: '', city: '', status: 'aktif' };
                    }
                    this.showFormModal = true;
                },
                openDetailModal(item) {
                    this.selectedDetail = item;
                    this.showDetailModal = true;
                },
                saveSupplier() {
                    if (this.modalMode === 'create') {
                        this.suppliers.push({ ...this.formData });
                    } else {
                        const index = this.suppliers.findIndex(s => s.id === this.formData.id);
                        if (index !== -1) this.suppliers[index] = { ...this.formData };
                    }
                    this.showFormModal = false;
                    lucide.createIcons();
                },
                deleteSupplier(id) {
                    if (confirm('Yakin ingin menghapus data supplier ini?')) {
                        this.suppliers = this.suppliers.filter(s => s.id !== id);
                    }
                }
            }
        }
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>
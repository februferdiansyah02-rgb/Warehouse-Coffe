<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kategori Kopi - KOPI.ONE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#12100E] text-[#E0D8D0] font-sans min-h-screen flex antialiased" x-data="kategoriPage(@js($categories->map(function($c) {
    return [
        'id' => $c->id,
        'name' => $c->name,
        'description' => $c->description,
        'total_stock' => (float) $c->total_stock,
        'total_variants' => (int) $c->total_variants,
    ];
})))">

    <x-sidebar />

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden md:pl-64 pl-0">
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex md:hidden justify-between items-center">
            <h1 class="font-bold text-white text-sm">KOPI.ONE</h1>
            <span class="text-xs text-[#D99B52]">{{ Auth::user()->name }}</span>
        </header>

        <!-- Header Topbar -->
        <header class="bg-[#181512] border-b border-[#26211C] p-4 flex justify-between items-center px-8">
            <div class="relative w-96">
                <input type="text" x-model="searchQuery" placeholder="Cari nama atau deskripsi kategori..." 
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

        <!-- Main Body -->
        <main class="p-8 space-y-6 max-w-7xl w-full mx-auto">

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="bg-emerald-950/70 border border-emerald-800/80 text-emerald-300 text-xs px-4 py-3 rounded-xl flex items-center space-x-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-950/70 border border-rose-800/80 text-rose-300 text-xs px-4 py-3 rounded-xl flex items-center space-x-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Section Title & Add Button -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Kategori Biji Kopi</h2>
                    <p class="text-xs text-stone-400 mt-1">Pengelompokan jenis biji kopi berdasarkan varietal dan metode pengolahan. Stok dihitung otomatis dari barang masuk.</p>
                </div>
                <button @click="openCreateModal()" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>

            <!-- GRID CARDS KATEGORI KOPI DARI DATABASE -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="cat in filteredCategories" :key="cat.id">
                    <div class="bg-[#181512] border border-[#26211C] rounded-2xl overflow-hidden hover:border-[#D99B52] transition group flex flex-col justify-between">
                        <div>
                            <div class="h-36 w-full relative overflow-hidden bg-[#26211C]">
                                <img :src="getImage(cat.name)" 
                                     :alt="cat.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-80">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#181512] via-transparent to-transparent"></div>
                                <span class="absolute top-3 right-3 bg-[#D99B52] text-[#12100E] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#D99B52]"
                                      x-text="cat.total_variants + ' Varian Kopi'">
                                </span>
                            </div>
                            <div class="p-5 space-y-2">
                                <h3 class="text-lg font-bold text-white group-hover:text-[#D99B52] transition" x-text="cat.name"></h3>
                                <p class="text-xs text-stone-400 leading-relaxed min-h-[36px]" x-text="cat.description || 'Tidak ada deskripsi.'"></p>
                            </div>
                        </div>

                        <div class="p-5 pt-0">
                            <div class="pt-3 flex justify-between items-center border-t border-[#26211C] text-xs">
                                <span class="text-stone-500">
                                    Total Stok: 
                                    <strong :class="cat.total_stock > 0 ? 'text-emerald-400' : 'text-stone-400'" 
                                            x-text="cat.total_stock + ' kg'"></strong>
                                </span>
                                <div class="flex items-center space-x-2">
                                    <button @click="openEditModal(cat)" class="text-stone-400 hover:text-[#D99B52] p-1" title="Edit Kategori">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button @click="confirmDelete(cat)" class="text-stone-400 hover:text-rose-400 p-1" title="Hapus Kategori">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State jika tidak ada kategori -->
            <div x-show="filteredCategories.length === 0" class="bg-[#181512] border border-[#26211C] rounded-2xl p-12 text-center space-y-4">
                <div class="w-12 h-12 bg-[#26211C] text-[#D99B52] rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="coffee" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-base">Belum Ada Kategori Kopi</h4>
                    <p class="text-xs text-stone-400 mt-1 max-w-sm mx-auto">Tambahkan kategori biji kopi baru untuk mulai mengelompokkan stok dan transaksi barang masuk.</p>
                </div>
                <button @click="openCreateModal()" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-medium text-xs px-4 py-2.5 rounded-xl shadow transition inline-flex items-center space-x-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Kategori Pertama</span>
                </button>
            </div>

        </main>
    </div>

    <!-- MODAL FORM TAMBAH / EDIT KATEGORI -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/75 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        
        <div class="bg-[#181512] border border-[#26211C] rounded-2xl w-full max-w-md p-6 space-y-4 shadow-2xl"
             @click.away="showModal = false">
            
            <div class="flex justify-between items-center border-b border-[#26211C] pb-3">
                <h3 class="font-bold text-white text-base" x-text="isEdit ? 'Edit Kategori Kopi' : 'Tambah Kategori Kopi'"></h3>
                <button @click="showModal = false" class="text-stone-500 hover:text-white text-lg">&times;</button>
            </div>

            <form :action="isEdit ? '/kategori/' + form.id : '{{ route('kategori.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs text-stone-300 font-medium mb-1">Nama Kategori <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Contoh: Arabika Gayo, Robusta, dsb."
                           class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]">
                </div>

                <div>
                    <label class="block text-xs text-stone-300 font-medium mb-1">Deskripsi / Catatan Karakter</label>
                    <textarea name="description" x-model="form.description" rows="3" placeholder="Karakteristik aroma, rasa, atau proses pengolahan..."
                              class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-[#D99B52]"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-2 border-t border-[#26211C]">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs text-stone-400 hover:text-white">Batal</button>
                    <button type="submit" class="bg-[#D99B52] hover:bg-[#c28742] text-[#12100E] font-bold text-xs px-4 py-2 rounded-xl transition">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- HIDDEN FORM UNTUK DELETE -->
    <form id="delete-category-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function kategoriPage(initialCategories) {
            return {
                categories: initialCategories || [],
                searchQuery: '',
                showModal: false,
                isEdit: false,
                form: { id: null, name: '', description: '' },
                get filteredCategories() {
                    const q = this.searchQuery.toLowerCase();
                    return this.categories.filter(c => {
                        return (c.name || '').toLowerCase().includes(q) ||
                               (c.description || '').toLowerCase().includes(q);
                    });
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.form = { id: null, name: '', description: '' };
                    this.showModal = true;
                },
                openEditModal(cat) {
                    this.isEdit = true;
                    this.form = { id: cat.id, name: cat.name, description: cat.description || '' };
                    this.showModal = true;
                },
                confirmDelete(cat) {
                    if (confirm('Yakin ingin menghapus kategori "' + cat.name + '"?')) {
                        const form = document.getElementById('delete-category-form');
                        form.action = '/kategori/' + cat.id;
                        form.submit();
                    }
                },
                getImage(name) {
                    const lower = (name || '').toLowerCase();
                    if (lower.includes('robusta')) {
                        return 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80';
                    }
                    if (lower.includes('liberika') || lower.includes('liberica') || lower.includes('excelsa')) {
                        return 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80';
                    }
                    if (lower.includes('blend')) {
                        return 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80';
                    }
                    return 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80';
                }
            };
        }
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Kode Kopi</label>
        <input type="text" name="code" value="{{ old('code', $coffee->code ?? '') }}" required
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
        @error('code')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Nama Kopi</label>
        <input type="text" name="name" value="{{ old('name', $coffee->name ?? '') }}" required
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
        @error('name')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Kategori</label>
        <select name="category_id" class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
            <option value="">Pilih kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $coffee->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Asal Daerah</label>
        <input type="text" name="origin" value="{{ old('origin', $coffee->origin ?? '') }}"
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Jenis Proses</label>
        <input type="text" name="process" value="{{ old('process', $coffee->process ?? '') }}" placeholder="Full Washed"
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Bentuk Kopi</label>
        <input type="text" name="form" value="{{ old('form', $coffee->form ?? '') }}" placeholder="Green Bean"
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Grade</label>
        <input type="text" name="grade" value="{{ old('grade', $coffee->grade ?? '') }}" placeholder="Grade 1"
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Stok</label>
        <input type="number" step="0.01" min="0" name="stock" value="{{ old('stock', $coffee->stock ?? 0) }}" required
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Satuan</label>
        <input type="text" name="unit" value="{{ old('unit', $coffee->unit ?? 'Kg') }}" required
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Harga Beli</label>
        <input type="number" step="0.01" min="0" name="purchase_price" value="{{ old('purchase_price', $coffee->purchase_price ?? 0) }}" required
               class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
    </div>
    <div>
        <label class="block text-xs text-stone-400 mb-1.5">Status</label>
        <select name="status" class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">
            @foreach(['Aktif','Tidak Aktif'] as $st)
                <option value="{{ $st }}" @selected(old('status', $coffee->status ?? 'Aktif') === $st)>{{ $st }}</option>
            @endforeach
        </select>
    </div>
</div>
<div>
    <label class="block text-xs text-stone-400 mb-1.5">Deskripsi</label>
    <textarea name="description" rows="3" class="w-full bg-[#12100E] border border-[#26211C] rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#D99B52]">{{ old('description', $coffee->description ?? '') }}</textarea>
</div>
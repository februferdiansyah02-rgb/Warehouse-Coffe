<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Stok Kopi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <form method="POST" action="{{ route('inventory.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="item_name" class="block text-sm font-medium text-gray-700">Nama Item</label>
                            <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                            @error('item_name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category_id" id="category_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">Pilih kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                <select name="supplier_id" id="supplier_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">Pilih supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="stock_kg" class="block text-sm font-medium text-gray-700">Stok (kg)</label>
                                <input type="number" step="0.01" min="0" name="stock_kg" id="stock_kg" value="{{ old('stock_kg', 0) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                @error('stock_kg')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price_per_kg" class="block text-sm font-medium text-gray-700">Harga per kg (Rp)</label>
                                <input type="number" step="0.01" min="0" name="price_per_kg" id="price_per_kg" value="{{ old('price_per_kg', 0) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                @error('price_per_kg')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
                            <a href="{{ route('inventory.index') }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold rounded-lg transition">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white text-sm font-semibold rounded-lg shadow transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
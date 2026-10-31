<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Menampilkan daftar seluruh stok kopi
     */
    public function index()
    {
        $items = Item::with(['category', 'supplier'])->latest()->get();

        return view('inventory.index', compact('items'));
    }

    /**
     * Menampilkan form tambah stok kopi
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('inventory.create', compact('categories', 'suppliers'));
    }

    /**
     * Menyimpan data stok kopi baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock_kg' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        Item::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Stok biji kopi berhasil ditambahkan!');
    }

    /**
     * Menghapus stok kopi dari database
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('inventory.index')->with('success', 'Data stok berhasil dihapus!');
    }
}

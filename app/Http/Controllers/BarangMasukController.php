<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coffee;
use App\Models\IncomingGood;
use App\Models\IncomingGoodItem;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $incomingGoods = IncomingGood::with(['items.coffee.category', 'items.category', 'supplier'])
            ->latest()
            ->get();

        $transactions = $incomingGoods->map(function (IncomingGood $trx) {
            return [
                'id' => $trx->id,
                'code' => $trx->code,
                'date' => $trx->date?->format('Y-m-d'),
                'supplier' => $trx->supplier ?: ($trx->supplier?->name ?? '-'),
                'supplier_id' => $trx->supplier_id,
                'invoice' => $trx->invoice,
                'officer' => $trx->officer,
                'note' => $trx->note,
                'totalKg' => (float) $trx->total_kg,
                'totalPrice' => (float) $trx->total_price,
                'items' => $trx->items->map(function (IncomingGoodItem $item) {
                    return [
                        'name' => $item->name,
                        'qty' => (float) $item->qty,
                        'price' => (float) $item->price,
                        'coffee_id' => $item->coffee_id,
                        'category_id' => $item->category_id,
                        'category' => $item->category?->name ?? $item->coffee?->category?->name,
                    ];
                })->toArray(),
            ];
        })->toArray();

        $coffees = Coffee::with('category')->latest()->get();
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('barang-masuk.index', compact('transactions', 'coffees', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:incoming_goods,code',
            'date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier' => 'nullable|string|max:255',
            'invoice' => 'required|string|max:255',
            'officer' => 'required|string|max:255',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.coffee_id' => 'nullable',
            'items.*.name' => 'required|string|max:255',
            'items.*.category_id' => 'nullable',
            'items.*.category_name' => 'nullable|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // --- Resolve Supplier: jika manual (text), buat data Supplier baru ---
        $supplierId = null;
        if (! empty($validated['supplier_id']) && $validated['supplier_id'] !== 'manual') {
            $supplierId = $validated['supplier_id'];
        } elseif (! empty($validated['supplier'])) {
            $supplier = Supplier::firstOrCreate(['name' => $validated['supplier']]);
            $supplierId = $supplier->id;
        }

        $supplierName = null;
        if ($supplierId) {
            $supplierName = Supplier::find($supplierId)?->name;
        } elseif (! empty($validated['supplier'])) {
            $supplierName = $validated['supplier'];
        }

        $created = DB::transaction(function () use ($validated, $supplierId, $supplierName) {
            $totalKg = 0;
            $totalPrice = 0;

            foreach ($validated['items'] as $row) {
                $totalKg += (float) $row['qty'];
                $totalPrice += ((float) $row['qty']) * ((float) $row['price']);
            }

            $incoming = IncomingGood::create([
                'code' => $validated['code'],
                'date' => $validated['date'],
                'supplier_id' => $supplierId,
                'supplier' => $supplierName,
                'invoice' => $validated['invoice'],
                'officer' => $validated['officer'],
                'note' => $validated['note'] ?? null,
                'total_kg' => $totalKg,
                'total_price' => $totalPrice,
            ]);

            foreach ($validated['items'] as $row) {
                $coffee = ! empty($row['coffee_id']) && is_numeric($row['coffee_id']) ? Coffee::find($row['coffee_id']) : null;

                $categoryId = ! empty($row['category_id']) && is_numeric($row['category_id']) ? (int) $row['category_id'] : null;

                if (! empty($row['category_name'])) {
                    $category = Category::firstOrCreate(['name' => trim($row['category_name'])]);
                    $categoryId = $category->id;
                } elseif (! $categoryId && $coffee?->category_id) {
                    $categoryId = $coffee->category_id;
                }

                $itemName = ! empty($row['name']) ? $row['name'] : ($coffee?->name ?? 'Item Tanpa Nama');

                $incoming->items()->create([
                    'coffee_id' => $coffee?->id,
                    'category_id' => $categoryId,
                    'name' => $itemName,
                    'qty' => $row['qty'],
                    'price' => $row['price'],
                    'subtotal' => ((float) $row['qty']) * ((float) $row['price']),
                ]);

                // --- Sinkronisasi ke model Coffee (data kopi) ---
                if ($coffee) {
                    $coffee->increment('stock', (float) $row['qty']);
                    $coffeeUpdate = ['purchase_price' => $row['price']];
                    if ($categoryId && ! $coffee->category_id) {
                        $coffeeUpdate['category_id'] = $categoryId;
                    }
                    $coffee->update($coffeeUpdate);
                } elseif (! empty($row['name'])) {
                    $code = 'K'.str_pad((string) (Coffee::count() + 1), 3, '0', STR_PAD_LEFT);
                    $coffee = Coffee::create([
                        'code' => $code,
                        'name' => $itemName,
                        'category_id' => $categoryId,
                        'stock' => $row['qty'],
                        'unit' => 'Kg',
                        'purchase_price' => $row['price'],
                        'status' => 'Aktif',
                    ]);
                }

                // --- Sinkronisasi ke model Item (dashboard / stok gudang) ---
                if ($coffee) {
                    $existingItem = Item::where('item_name', $itemName)->first();
                    if ($existingItem) {
                        $existingItem->increment('stock_kg', (float) $row['qty']);
                        $updateData = ['price_per_kg' => $row['price']];
                        if ($supplierId) {
                            $updateData['supplier_id'] = $supplierId;
                        }
                        if ($categoryId) {
                            $updateData['category_id'] = $categoryId;
                        }
                        $existingItem->update($updateData);
                    } else {
                        Item::create([
                            'item_name' => $itemName,
                            'category_id' => $categoryId,
                            'supplier_id' => $supplierId,
                            'stock_kg' => (float) $row['qty'],
                            'price_per_kg' => (float) $row['price'],
                        ]);
                    }
                }
            }

            return $incoming->load(['items.coffee.category', 'items.category', 'supplier']);
        });

        $transaction = [
            'id' => $created->id,
            'code' => $created->code,
            'date' => $created->date?->format('Y-m-d'),
            'supplier' => $created->supplier ?: ($created->supplier?->name ?? '-'),
            'supplier_id' => $created->supplier_id,
            'invoice' => $created->invoice,
            'officer' => $created->officer,
            'note' => $created->note,
            'totalKg' => (float) $created->total_kg,
            'totalPrice' => (float) $created->total_price,
            'items' => $created->items->map(function (IncomingGoodItem $item) {
                return [
                    'name' => $item->name,
                    'qty' => (float) $item->qty,
                    'price' => (float) $item->price,
                    'coffee_id' => $item->coffee_id,
                    'category_id' => $item->category_id,
                    'category' => $item->category?->name ?? $item->coffee?->category?->name,
                ];
            })->toArray(),
        ];

        return response()->json([
            'message' => 'Transaksi '.$created->code.' berhasil disimpan!',
            'transaction' => $transaction,
            'coffees' => Coffee::with('category')->latest()->get(),
            'categories' => Category::all(),
        ]);
    }

    public function destroy(IncomingGood $barang_masuk)
    {
        DB::transaction(function () use ($barang_masuk) {
            foreach ($barang_masuk->items as $item) {
                if ($item->coffee_id) {
                    $coffee = Coffee::find($item->coffee_id);
                    if ($coffee) {
                        $coffee->decrement('stock', (float) $item->qty);
                    }
                }

                // --- Sinkronisasi ke model Item (dashboard / stok gudang) ---
                $existingItem = Item::where('item_name', $item->name)->first();
                if ($existingItem) {
                    $existingItem->decrement('stock_kg', (float) $item->qty);
                }
            }
            $barang_masuk->delete();
        });

        return response()->json(['message' => 'Transaksi dibatalkan.']);
    }
}

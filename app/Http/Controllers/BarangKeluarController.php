<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coffee;
use App\Models\Item;
use App\Models\OutgoingGood;
use App\Models\OutgoingGoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = OutgoingGood::with(['items.coffee.category', 'items.category'])
            ->latest();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        // Filter tujuan
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $outgoingGoods = $query->get();

        $transactions = $outgoingGoods->map(function (OutgoingGood $trx) {
            return [
                'id' => $trx->id,
                'code' => $trx->code,
                'date' => $trx->date?->format('Y-m-d'),
                'date_formatted' => $trx->date?->format('d M Y'),
                'purpose' => $trx->purpose,
                'recipient_name' => $trx->recipient_name,
                'reference_no' => $trx->reference_no,
                'officer' => $trx->officer,
                'note' => $trx->note,
                'totalKg' => (float) $trx->total_kg,
                'totalPrice' => (float) $trx->total_price,
                'items' => $trx->items->map(function (OutgoingGoodItem $item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'category' => $item->category?->name ?? $item->coffee?->category?->name ?? '-',
                        'available_stock' => (float) $item->available_stock,
                        'qty' => (float) $item->qty,
                        'unit' => $item->unit,
                        'price' => (float) $item->price,
                        'subtotal' => (float) $item->subtotal,
                        'coffee_id' => $item->coffee_id,
                        'category_id' => $item->category_id,
                    ];
                })->toArray(),
            ];
        })->toArray();

        $coffees = Coffee::with('category')->latest()->get();
        $categories = Category::all();

        $purposes = [
            'Penjualan' => 'Penjualan',
            'Produksi' => 'Produksi',
            'Pengiriman' => 'Pengiriman',
            'Sampel' => 'Sampel',
            'Penggunaan Internal' => 'Penggunaan Internal',
            'Rusak / Afkir' => 'Rusak / Afkir',
        ];

        return view('barang-keluar.index', compact(
            'transactions',
            'coffees',
            'categories',
            'purposes'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:outgoing_goods,code',
            'date' => 'required|date',
            'purpose' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'officer' => 'required|string|max:255',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.coffee_id' => 'nullable|exists:coffees,id',
            'items.*.category_id' => 'nullable|exists:categories,id',
            'items.*.name' => 'required|string|max:255',
            'items.*.available_stock' => 'required|numeric|min:0',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        $validated['total_kg'] = collect($validated['items'])->sum(fn ($i) => (float) $i['qty']);
        $validated['total_price'] = collect($validated['items'])->sum(fn ($i) => (float) $i['subtotal']);

        $created = DB::transaction(function () use ($validated) {
            $outgoing = OutgoingGood::create([
                'code' => $validated['code'],
                'date' => $validated['date'],
                'purpose' => $validated['purpose'],
                'recipient_name' => $validated['recipient_name'],
                'reference_no' => $validated['reference_no'] ?? null,
                'officer' => $validated['officer'],
                'note' => $validated['note'] ?? null,
                'total_kg' => $validated['total_kg'],
                'total_price' => $validated['total_price'],
            ]);

            foreach ($validated['items'] as $row) {
                $coffee = ! empty($row['coffee_id']) ? Coffee::find($row['coffee_id']) : null;

                $outgoing->items()->create([
                    'coffee_id' => $coffee?->id,
                    'category_id' => $row['category_id'] ?? ($coffee?->category_id),
                    'name' => $row['name'],
                    'available_stock' => $row['available_stock'],
                    'qty' => $row['qty'],
                    'unit' => $row['unit'] ?? 'Kg',
                    'price' => $row['price'],
                    'subtotal' => $row['subtotal'],
                ]);

                // Kurangi stok kopi (master data kopi) secara otomatis
                if ($coffee) {
                    $coffee->decrement('stock', (float) $row['qty']);
                }

                // Kurangi stok gudang (Item) agar selalu sinkron dengan transaksi keluar
                $itemName = ! empty($row['name']) ? $row['name'] : ($coffee?->name ?? 'Item Tanpa Nama');
                $gudangItem = Item::where('item_name', $itemName)->first();
                if ($gudangItem) {
                    $gudangItem->decrement('stock_kg', (float) $row['qty']);
                }
            }

            return $outgoing->load(['items.coffee.category', 'items.category']);
        });

        $transaction = [
            'id' => $created->id,
            'code' => $created->code,
            'date' => $created->date?->format('Y-m-d'),
            'date_formatted' => $created->date?->format('d M Y'),
            'purpose' => $created->purpose,
            'recipient_name' => $created->recipient_name,
            'reference_no' => $created->reference_no,
            'officer' => $created->officer,
            'note' => $created->note,
            'totalKg' => (float) $created->total_kg,
            'totalPrice' => (float) $created->total_price,
            'items' => $created->items->map(function (OutgoingGoodItem $item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category?->name ?? $item->coffee?->category?->name ?? '-',
                    'available_stock' => (float) $item->available_stock,
                    'qty' => (float) $item->qty,
                    'unit' => $item->unit,
                    'price' => (float) $item->price,
                    'subtotal' => (float) $item->subtotal,
                    'coffee_id' => $item->coffee_id,
                    'category_id' => $item->category_id,
                ];
            })->toArray(),
        ];

        return response()->json([
            'message' => 'Transaksi '.$created->code.' berhasil disimpan! Stok kopi telah dikurangi.',
            'transaction' => $transaction,
        ]);
    }

    public function show(OutgoingGood $barang_keluar)
    {
        $barang_keluar->load(['items.coffee.category', 'items.category']);

        return response()->json([
            'transaction' => [
                'id' => $barang_keluar->id,
                'code' => $barang_keluar->code,
                'date' => $barang_keluar->date?->format('Y-m-d'),
                'date_formatted' => $barang_keluar->date?->format('d M Y'),
                'purpose' => $barang_keluar->purpose,
                'recipient_name' => $barang_keluar->recipient_name,
                'reference_no' => $barang_keluar->reference_no,
                'officer' => $barang_keluar->officer,
                'note' => $barang_keluar->note,
                'totalKg' => (float) $barang_keluar->total_kg,
                'totalPrice' => (float) $barang_keluar->total_price,
                'items' => $barang_keluar->items->map(function (OutgoingGoodItem $item) {
                    return [
                        'name' => $item->name,
                        'category' => $item->category?->name ?? $item->coffee?->category?->name ?? '-',
                        'available_stock' => (float) $item->available_stock,
                        'qty' => (float) $item->qty,
                        'unit' => $item->unit,
                        'price' => (float) $item->price,
                        'subtotal' => (float) $item->subtotal,
                    ];
                })->toArray(),
            ],
        ]);
    }

    public function update(Request $request, OutgoingGood $barang_keluar)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:outgoing_goods,code,'.$barang_keluar->id,
            'date' => 'required|date',
            'purpose' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'reference_no' => 'nullable|string|max:255',
            'officer' => 'required|string|max:255',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.coffee_id' => 'nullable|exists:coffees,id',
            'items.*.category_id' => 'nullable|exists:categories,id',
            'items.*.name' => 'required|string|max:255',
            'items.*.available_stock' => 'required|numeric|min:0',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        $validated['total_kg'] = collect($validated['items'])->sum(fn ($i) => (float) $i['qty']);
        $validated['total_price'] = collect($validated['items'])->sum(fn ($i) => (float) $i['subtotal']);

        DB::transaction(function () use ($barang_keluar, $validated) {
            // Kembalikan stok lama (master kopi + gudang)
            foreach ($barang_keluar->items as $oldItem) {
                if ($oldItem->coffee_id) {
                    $coffee = Coffee::find($oldItem->coffee_id);
                    if ($coffee) {
                        $coffee->increment('stock', (float) $oldItem->qty);
                    }
                }

                $oldItemName = ! empty($oldItem->name) ? $oldItem->name : ($oldItem->coffee?->name ?? 'Item Tanpa Nama');
                $oldGudangItem = Item::where('item_name', $oldItemName)->first();
                if ($oldGudangItem) {
                    $oldGudangItem->increment('stock_kg', (float) $oldItem->qty);
                }
            }

            // Hapus item lama
            $barang_keluar->items()->delete();

            // Update data utama
            $barang_keluar->update([
                'code' => $validated['code'],
                'date' => $validated['date'],
                'purpose' => $validated['purpose'],
                'recipient_name' => $validated['recipient_name'],
                'reference_no' => $validated['reference_no'] ?? null,
                'officer' => $validated['officer'],
                'note' => $validated['note'] ?? null,
                'total_kg' => $validated['total_kg'],
                'total_price' => $validated['total_price'],
            ]);

            // Buat item baru dan kurangi stok
            foreach ($validated['items'] as $row) {
                $coffee = ! empty($row['coffee_id']) ? Coffee::find($row['coffee_id']) : null;

                $barang_keluar->items()->create([
                    'coffee_id' => $coffee?->id,
                    'category_id' => $row['category_id'] ?? ($coffee?->category_id),
                    'name' => $row['name'],
                    'available_stock' => $row['available_stock'],
                    'qty' => $row['qty'],
                    'unit' => $row['unit'] ?? 'Kg',
                    'price' => $row['price'],
                    'subtotal' => $row['subtotal'],
                ]);

                if ($coffee) {
                    $coffee->decrement('stock', (float) $row['qty']);
                }

                $itemName = ! empty($row['name']) ? $row['name'] : ($coffee?->name ?? 'Item Tanpa Nama');
                $gudangItem = Item::where('item_name', $itemName)->first();
                if ($gudangItem) {
                    $gudangItem->decrement('stock_kg', (float) $row['qty']);
                }
            }
        });

        $barang_keluar->load(['items.coffee.category', 'items.category']);

        return response()->json([
            'message' => 'Transaksi '.$barang_keluar->code.' berhasil diperbarui.',
            'transaction' => [
                'id' => $barang_keluar->id,
                'code' => $barang_keluar->code,
                'date' => $barang_keluar->date?->format('Y-m-d'),
                'purpose' => $barang_keluar->purpose,
                'recipient_name' => $barang_keluar->recipient_name,
                'reference_no' => $barang_keluar->reference_no,
                'officer' => $barang_keluar->officer,
                'note' => $barang_keluar->note,
                'totalKg' => (float) $barang_keluar->total_kg,
                'totalPrice' => (float) $barang_keluar->total_price,
                'items' => $barang_keluar->items->map(function (OutgoingGoodItem $item) {
                    return [
                        'name' => $item->name,
                        'category' => $item->category?->name ?? $item->coffee?->category?->name ?? '-',
                        'available_stock' => (float) $item->available_stock,
                        'qty' => (float) $item->qty,
                        'unit' => $item->unit,
                        'price' => (float) $item->price,
                        'subtotal' => (float) $item->subtotal,
                    ];
                })->toArray(),
            ],
        ]);
    }

    public function destroy(OutgoingGood $barang_keluar)
    {
        DB::transaction(function () use ($barang_keluar) {
            // Kembalikan stok kopi (master kopi + gudang)
            foreach ($barang_keluar->items as $item) {
                if ($item->coffee_id) {
                    $coffee = Coffee::find($item->coffee_id);
                    if ($coffee) {
                        $coffee->increment('stock', (float) $item->qty);
                    }
                }

                $itemName = ! empty($item->name) ? $item->name : ($item->coffee?->name ?? 'Item Tanpa Nama');
                $gudangItem = Item::where('item_name', $itemName)->first();
                if ($gudangItem) {
                    $gudangItem->increment('stock_kg', (float) $item->qty);
                }
            }
            $barang_keluar->delete();
        });

        return response()->json(['message' => 'Transaksi dibatalkan dan stok telah dikembalikan.']);
    }
}

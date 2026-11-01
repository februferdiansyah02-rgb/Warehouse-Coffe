<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coffee;
use App\Models\IncomingGood;
use App\Models\IncomingGoodItem;
use App\Models\Item;
use App\Models\OutgoingGood;
use App\Models\OutgoingGoodItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan (daftar laporan).
     */
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * 1. Laporan Stok Kopi.
     * Menampilkan seluruh stok kopi, stok menipis, dapat difilter berdasarkan kategori.
     */
    public function stok(Request $request)
    {
        $query = Item::with(['category', 'supplier'])->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%");
            });
        }

        $items = $query->get();

        $categories = Category::all();

        $lowStockItems = $items->filter(fn (Item $item) => (float) $item->stock_kg < (float) $item->minimum_stock);

        $summary = [
            'total_kopi' => $items->count(),
            'total_stok' => (float) $items->sum('stock_kg'),
            'total_menipis' => $lowStockItems->count(),
        ];

        if ($request->boolean('export')) {
            return $this->downloadCsv(
                'laporan-stok-kopi-'.now()->format('Y-m-d').'.csv',
                ['Nama Kopi', 'Kategori', 'Stok Tersedia (kg)', 'Stok Minimum (kg)', 'Status Stok'],
                $items->map(fn (Item $item) => [
                    $item->item_name,
                    optional($item->category)->name ?? '-',
                    (float) $item->stock_kg,
                    (float) $item->minimum_stock,
                    (float) $item->stock_kg < (float) $item->minimum_stock ? 'Stok Menipis' : 'Aman',
                ])->toArray()
            );
        }

        return view('laporan.stok', compact('items', 'lowStockItems', 'summary', 'categories'));
    }

    /**
     * 2. Laporan Barang Masuk.
     * Menampilkan riwayat penerimaan kopi dari supplier.
     */
    public function masuk(Request $request)
    {
        $query = IncomingGood::with(['supplier', 'items.coffee.category', 'items.category'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $transactions = $query->get();

        $suppliers = Supplier::all();

        $totalKg = (float) $transactions->sum('total_kg');
        $totalPrice = (float) $transactions->sum('total_price');

        if ($request->boolean('export')) {
            $rows = [];

            foreach ($transactions as $trx) {
                $itemNames = $trx->items->map(fn ($item) => $item->name)->all();

                $rows[] = [
                    optional($trx->date)->format('d/m/Y'),
                    $trx->code,
                    optional($trx->supplier)->name ?? $trx->supplier ?? '-',
                    implode(', ', $itemNames),
                    (float) $trx->total_kg,
                    (float) $trx->total_price,
                ];
            }

            return $this->downloadCsv(
                'laporan-barang-masuk-'.now()->format('Y-m-d').'.csv',
                ['Tanggal', 'Kode Transaksi', 'Supplier', 'Nama Kopi', 'Jumlah (kg)', 'Total Harga'],
                $rows
            );
        }

        return view('laporan.masuk', compact('transactions', 'totalKg', 'totalPrice', 'suppliers'));
    }

    /**
     * 3. Laporan Barang Keluar.
     * Menampilkan seluruh aktivitas pengeluaran kopi dari gudang.
     */
    public function keluar(Request $request)
    {
        $query = OutgoingGood::with(['items.coffee.category', 'items.category'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        $transactions = $query->get();

        $purposes = [
            'Penjualan' => 'Penjualan',
            'Produksi' => 'Produksi',
            'Pengiriman' => 'Pengiriman',
            'Sampel' => 'Sampel',
            'Penggunaan Internal' => 'Penggunaan Internal',
            'Rusak / Afkir' => 'Rusak / Afkir',
        ];

        $totalKg = (float) $transactions->sum('total_kg');

        if ($request->boolean('export')) {
            $rows = [];

            foreach ($transactions as $trx) {
                $itemNames = $trx->items->map(fn ($item) => $item->name)->all();

                $rows[] = [
                    optional($trx->date)->format('d/m/Y'),
                    $trx->code,
                    $trx->purpose,
                    implode(', ', $itemNames),
                    (float) $trx->total_kg,
                ];
            }

            return $this->downloadCsv(
                'laporan-barang-keluar-'.now()->format('Y-m-d').'.csv',
                ['Tanggal', 'Kode Transaksi', 'Tujuan', 'Nama Kopi', 'Jumlah (kg)'],
                $rows
            );
        }

        return view('laporan.keluar', compact('transactions', 'totalKg', 'purposes'));
    }

    /**
     * 4. Laporan Pergerakan Stok.
     * Menampilkan riwayat perubahan stok (masuk, keluar, saldo).
     */
    public function pergerakan(Request $request)
    {
        $incomingItems = IncomingGoodItem::with(['coffee', 'incomingGood'])
            ->whereHas('incomingGood', fn ($q) => $q->whereDate('date', '>=', $request->filled('date_from') ? $request->date_from : '1900-01-01'))
            ->get();

        $outgoingItems = OutgoingGoodItem::with(['coffee', 'outgoingGood'])
            ->whereHas('outgoingGood', fn ($q) => $q->whereDate('date', '>=', $request->filled('date_from') ? $request->date_from : '1900-01-01'))
            ->get();

        $movements = collect();

        foreach ($incomingItems as $item) {
            $movements->push([
                'name' => $item->coffee?->name ?? $item->name,
                'date' => optional($item->incomingGood)->date,
                'type' => 'masuk',
                'qty' => (float) $item->qty,
            ]);
        }

        foreach ($outgoingItems as $item) {
            $movements->push([
                'name' => $item->coffee?->name ?? $item->name,
                'date' => optional($item->outgoingGood)->date,
                'type' => 'keluar',
                'qty' => (float) $item->qty,
            ]);
        }

        $saldoByCoffee = [];

        $rows = [];

        foreach ($movements->sortBy('date')->values() as $movement) {
            $key = $movement['name'];
            $currentSaldo = $saldoByCoffee[$key] ?? 0;
            $saldoByCoffee[$key] = $movement['type'] === 'masuk'
                ? $currentSaldo + $movement['qty']
                : $currentSaldo - $movement['qty'];

            $rows[] = [
                'date' => optional($movement['date'])->format('d/m/Y'),
                'name' => $key,
                'type' => $movement['type'],
                'masuk' => $movement['type'] === 'masuk' ? $movement['qty'] : 0,
                'keluar' => $movement['type'] === 'keluar' ? $movement['qty'] : 0,
                'saldo' => $saldoByCoffee[$key],
            ];
        }

        return view('laporan.pergerakan', compact('rows'));
    }

    /**
     * 5. Laporan Analisis Stok Bulanan.
     * Menampilkan perkembangan stok setiap bulan (grafik + total masuk/keluar).
     */
    public function analisis(Request $request)
    {
        $start = $request->filled('date_from') ? Carbon::parse($request->input('date_from'))->startOfMonth() : Carbon::now()->startOfMonth()->subMonths(11);
        $end = $request->filled('date_to') ? Carbon::parse($request->input('date_to'))->endOfMonth() : Carbon::now()->endOfMonth();

        $incomingItems = IncomingGoodItem::with(['coffee', 'incomingGood'])
            ->whereHas('incomingGood', fn ($q) => $q->whereBetween('date', [$start, $end]))
            ->get();

        $outgoingItems = OutgoingGoodItem::with(['coffee', 'outgoingGood'])
            ->whereHas('outgoingGood', fn ($q) => $q->whereBetween('date', [$start, $end]))
            ->get();

        $monthly = [];

        // Kumpulkan pergerakan per nama kopi
        $movementsByCoffee = [];

        foreach ($incomingItems as $item) {
            $date = optional($item->incomingGood)->date;
            if (! $date) {
                continue;
            }
            $movementsByCoffee[$item->coffee?->name ?? $item->name][] = [
                'date' => $date,
                'type' => 'masuk',
                'qty' => (float) $item->qty,
            ];
        }

        foreach ($outgoingItems as $item) {
            $date = optional($item->outgoingGood)->date;
            if (! $date) {
                continue;
            }
            $movementsByCoffee[$item->coffee?->name ?? $item->name][] = [
                'date' => $date,
                'type' => 'keluar',
                'qty' => (float) $item->qty,
            ];
        }

        // Hitung saldo per kopi per bulan
        foreach ($movementsByCoffee as $name => $list) {
            usort($list, fn ($a, $b) => $a['date'] <=> $b['date']);

            $saldo = 0;
            foreach ($list as $movement) {
                $monthKey = $movement['date']->format('Y-m');
                $monthly[$monthKey]['masuk'] = ($monthly[$monthKey]['masuk'] ?? 0) + ($movement['type'] === 'masuk' ? $movement['qty'] : 0);
                $monthly[$monthKey]['keluar'] = ($monthly[$monthKey]['keluar'] ?? 0) + ($movement['type'] === 'keluar' ? $movement['qty'] : 0);
                $saldo += $movement['type'] === 'masuk' ? $movement['qty'] : -$movement['qty'];
                $monthly[$monthKey]['stok_akhir'] = $saldo;
            }
        }

        $monthlyTotals = collect($monthly)->mapWithKeys(fn ($data, $monthKey) => [
            $monthKey => [
                'masuk' => (float) ($data['masuk'] ?? 0),
                'keluar' => (float) ($data['keluar'] ?? 0),
                'stok_akhir' => (float) ($data['stok_akhir'] ?? 0),
            ],
        ])->all();

        $labels = [];
        $masukData = [];
        $keluarData = [];
        $stokData = [];

        foreach ($monthlyTotals as $monthKey => $data) {
            $labels[] = Carbon::parse($monthKey.'-01')->format('M Y');
            $masukData[] = (float) $data['masuk'];
            $keluarData[] = (float) $data['keluar'];
            $stokData[] = (float) $data['stok_akhir'];
        }

        $totalMasuk = (float) array_sum($masukData);
        $totalKeluar = (float) array_sum($keluarData);
        $stokAkhir = (float) (end($stokData) ?: 0);

        return view('laporan.analisis', [
            'monthly' => $monthlyTotals,
            'start' => $start,
            'end' => $end,
            'labels' => $labels,
            'masukData' => $masukData,
            'keluarData' => $keluarData,
            'stokData' => $stokData,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'stokAkhir' => $stokAkhir,
        ]);
    }

    /**
     * 6. Laporan Supplier.
     * Menampilkan total transaksi dan total pasokan dari setiap supplier.
     */
    public function supplier(Request $request)
    {
        $query = IncomingGood::with('supplier')->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->get();

        $suppliers = $transactions
            ->filter(fn (IncomingGood $trx) => $trx->supplier_id !== null)
            ->groupBy(fn (IncomingGood $trx) => $trx->supplier_id)
            ->map(fn (Collection $group, $supplierId) => [
                'name' => optional(Supplier::find($supplierId))->name ?? '-',
                'transactions' => $group->count(),
                'total_kg' => (float) $group->sum('total_kg'),
            ])
            ->sortByDesc(fn ($item) => $item['total_kg'])
            ->values();

        return view('laporan.supplier', compact('suppliers', 'transactions'));
    }

    /**
     * Download CSV laporan.
     */
    private function downloadCsv(string $filename, array $headers, array $rows)
    {
        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
        ]);
    }
}

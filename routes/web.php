<?php

use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\CoffeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WarehouseProfileController;
use App\Models\IncomingGood;
use App\Models\Item;
use App\Models\OutgoingGood;
use App\Models\Supplier;
use Illuminate\Support\Facades\Route;

// Redirect halaman awal: guest ke login, user terautentikasi ke inventory
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('inventory.index')
        : redirect()->route('login');
});

// Group route yang wajib login
Route::middleware(['auth'])->group(function () {

    // 1. Core CRUD Inventaris Gudang Kopi
    Route::resource('inventory', InventoryController::class);

    // Dashboard
    Route::get('/dashboard', function () {
        $items = Item::with(['category', 'supplier'])->latest()->get();

        // Stok menipis: stok di bawah ambang batas minimum per item
        $lowStockItems = Item::with(['category', 'supplier'])
            ->lowStock()
            ->orderBy('stock_kg', 'asc')
            ->get();

        // Transaksi terbaru: gabungkan barang masuk dan keluar, urutkan tanggal terbaru
        $incoming = IncomingGood::with('supplier')->get()->map(function ($trx) {
            // Kolom 'supplier' pada tabel incoming_goods menyimpan nama supplier (string),
            // bukan objek relasi — jadi jangan pakai "?->name" di sini.
            $partner = $trx->supplier instanceof Supplier
                ? $trx->supplier->name
                : ($trx->supplier ?: '-');

            return (object) [
                'type' => 'masuk',
                'code' => $trx->code,
                'date' => $trx->date,
                'partner' => $partner,
                'kg' => (float) $trx->total_kg,
                'sign' => '+',
            ];
        });

        $outgoing = OutgoingGood::get()->map(function ($trx) {
            return (object) [
                'type' => 'keluar',
                'code' => $trx->code,
                'date' => $trx->date,
                'partner' => $trx->recipient_name ?: '-',
                'kg' => (float) $trx->total_kg,
                'sign' => '-',
            ];
        });

        $recentTransactions = $incoming->concat($outgoing)
            ->sortByDesc(fn ($t) => $t->date)
            ->take(5)
            ->values();

        // Stok bersih bulan ini: total masuk dikurangi keluar dalam bulan berjalan
        $awalBulan = now()->startOfMonth();
        $masukBulanIni = IncomingGood::whereDate('date', '>=', $awalBulan)->sum('total_kg');
        $keluarBulanIni = OutgoingGood::whereDate('date', '>=', $awalBulan)->sum('total_kg');
        $nettoBulanIni = (float) $masukBulanIni - (float) $keluarBulanIni;

        return view('dashboard', compact('items', 'lowStockItems', 'recentTransactions', 'nettoBulanIni'));
    })->name('dashboard');

    // 2. Navigation Sidebar Routes
    // Resource Coffee di-alias menjadi /data-kopi agar cocok dengan route('data-kopi.index')
    Route::resource('data-kopi', CoffeeController::class)->names([
        'index' => 'data-kopi.index',
        'create' => 'data-kopi.create',
        'store' => 'data-kopi.store',
        'show' => 'data-kopi.show',
        'edit' => 'data-kopi.edit',
        'update' => 'data-kopi.update',
        'destroy' => 'data-kopi.destroy',
    ])->parameters(['data-kopi' => 'coffee']);

    Route::get('/kategori', function () {
        return view('kategori.index');
    })->name('kategori.index');

    Route::get('/supplier', function () {
        return view('supplier.index');
    })->name('supplier.index');

    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
    Route::delete('/barang-masuk/{barang_masuk}', [BarangMasukController::class, 'destroy'])->name('barang-masuk.destroy');

    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
Route::get('/barang-keluar/{barang_keluar}', [BarangKeluarController::class, 'show'])->name('barang-keluar.show');
Route::put('/barang-keluar/{barang_keluar}', [BarangKeluarController::class, 'update'])->name('barang-keluar.update');
Route::delete('/barang-keluar/{barang_keluar}', [BarangKeluarController::class, 'destroy'])->name('barang-keluar.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/laporan/masuk', [LaporanController::class, 'masuk'])->name('laporan.masuk');
    Route::get('/laporan/keluar', [LaporanController::class, 'keluar'])->name('laporan.keluar');
    Route::get('/laporan/pergerakan', [LaporanController::class, 'pergerakan'])->name('laporan.pergerakan');
    Route::get('/laporan/analisis', [LaporanController::class, 'analisis'])->name('laporan.analisis');
    Route::get('/laporan/supplier', [LaporanController::class, 'supplier'])->name('laporan.supplier');

    // 3. Route Profile Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 4. Profil Gudang (Warehouse Profile)
    Route::get('/pengaturan/gudang', [WarehouseProfileController::class, 'edit'])->name('warehouse.edit');
    Route::patch('/pengaturan/gudang', [WarehouseProfileController::class, 'update'])->name('warehouse.update');
});

require __DIR__.'/auth.php';

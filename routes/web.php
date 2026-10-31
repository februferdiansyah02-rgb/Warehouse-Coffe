<?php

use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\CoffeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Models\Item;
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

        return view('dashboard', compact('items'));
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

    Route::get('/laporan', function () {
        return view('laporan.index');
    })->name('laporan.index');

    Route::get('/pengguna', function () {
        return view('pengguna.index');
    })->name('pengguna.index');

    // 3. Route Profile Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

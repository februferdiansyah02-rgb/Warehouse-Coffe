<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coffee;
use Illuminate\Http\Request;

class CoffeeController extends Controller
{
    public function index()
    {
        $coffees = Coffee::with('category')->latest()->get();

        return view('coffee.index', compact('coffees'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('coffee.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coffees,code',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'origin' => 'nullable|string|max:255',
            'process' => 'nullable|string|max:255',
            'form' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50',
        ]);

        Coffee::create($validated);

        return redirect()->route('data-kopi.index')->with('success', 'Data kopi berhasil ditambahkan!');
    }

    public function show(Coffee $coffee)
    {
        return view('coffee.show', compact('coffee'));
    }

    public function edit(Coffee $coffee)
    {
        $categories = Category::all();

        return view('coffee.edit', compact('coffee', 'categories'));
    }

    public function update(Request $request, Coffee $coffee)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coffees,code,'.$coffee->id,
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'origin' => 'nullable|string|max:255',
            'process' => 'nullable|string|max:255',
            'form' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50',
        ]);

        $coffee->update($validated);

        return redirect()->route('data-kopi.index')->with('success', 'Data kopi berhasil diperbarui!');
    }

    public function destroy(Coffee $coffee)
    {
        $coffee->delete();

        return redirect()->route('data-kopi.index')->with('success', 'Data kopi berhasil dihapus!');
    }
}

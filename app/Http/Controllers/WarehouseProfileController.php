<?php

namespace App\Http\Controllers;

use App\Models\WarehouseProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WarehouseProfileController extends Controller
{
    public function edit(): View
    {
        $profile = WarehouseProfile::firstOrCreate([], ['name' => 'KOPI.ONE Warehouse']);

        return view('warehouse.edit', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = WarehouseProfile::firstOrCreate([], ['name' => 'KOPI.ONE Warehouse']);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'pic' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $data['logo'] = $request->file('logo')->store('warehouse', 'public');
        } else {
            unset($data['logo']);
        }

        $profile->update($data);

        return redirect()->route('warehouse.edit')->with('status', 'warehouse-updated');
    }
}
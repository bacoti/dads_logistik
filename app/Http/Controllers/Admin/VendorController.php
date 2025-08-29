<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::query()
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            })
            ->orderBy('name')
            ->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vendors,name',
        ]);

        Vendor::create($validated);

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function show(Vendor $vendor)
    {
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:vendors,name,' . $vendor->id,
        ]);

        $vendor->update($validated);

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil diperbarui!');
    }

    public function destroy(Vendor $vendor)
    {
        // Check if vendor is used in transactions
        if ($vendor->transactions()->count() > 0) {
            return redirect()->route('admin.vendors.index')
                ->with('error', 'Vendor tidak dapat dihapus karena masih digunakan dalam transaksi.');
        }

        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::latest();

        if ($search = $request->input('search')) {
            $query->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('penjual', 'like', "%{$search}%");
        }

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'penjual' => 'required|string',
            'whatsapp' => 'required|string',
            'harga' => 'nullable|numeric|min:0',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'is_ready' => 'nullable|boolean',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('products', 'public');
        }

        Product::create([
            'nama_produk' => $request->nama_produk,
            'penjual' => $request->penjual,
            'whatsapp' => $request->whatsapp,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'foto' => $path,
            'is_ready' => $request->boolean('is_ready', true),
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'penjual' => 'required|string',
            'whatsapp' => 'required|string',
            'harga' => 'nullable|numeric|min:0',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'is_ready' => 'nullable|boolean',
        ]);

        $path = $product->foto;
        if ($request->hasFile('foto')) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('foto')->store('products', 'public');
        }

        $product->update([
            'nama_produk' => $request->nama_produk,
            'penjual' => $request->penjual,
            'whatsapp' => $request->whatsapp,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'foto' => $path,
            'is_ready' => $request->boolean('is_ready'),
        ]);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Produk dihapus.');
    }
}

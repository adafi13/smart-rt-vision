<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    public function create()
    {
        return view('admin.products.create');
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

        try {
            DB::transaction(function () use ($request) {
                $path = null;
                if ($request->hasFile('foto')) {
                    $path = $request->file('foto')->store('products', 'public');
                }

                Product::create([
                    'tenant_id' => auth()->user()->tenant_id,
                    'nama_produk' => $request->nama_produk,
                    'penjual' => $request->penjual,
                    'whatsapp' => $request->whatsapp,
                    'harga' => $request->harga,
                    'kategori' => $request->kategori,
                    'deskripsi' => $request->deskripsi,
                    'foto' => $path,
                    'is_ready' => $request->boolean('is_ready', true),
                ]);
            });

            return redirect()->route('admin.umkm.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
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

        try {
            DB::transaction(function () use ($request, $product) {
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
            });

            return redirect()->route('admin.umkm.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::transaction(function () use ($product) {
                if ($product->foto) {
                    Storage::disk('public')->delete($product->foto);
                }
                
                $product->delete();
            });

            return back()->with('success', 'Produk dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}

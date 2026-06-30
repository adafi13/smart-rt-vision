<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $rwId = auth()->user()->rw->id;
        
        $query = News::where('rw_id', $rwId)->whereNull('tenant_id')->latest();

        if ($search = $request->input('search')) {
            $query->where('judul', 'like', "%{$search}%");
        }

        $newsList = $query->paginate(10)->withQueryString();

        return view('rw.news.index', ['newsList' => $newsList]);
    }

    public function create()
    {
        return view('rw.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'kategori' => 'required|string',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
            'is_penting' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $path = null;
                if ($request->hasFile('gambar')) {
                    $path = $request->file('gambar')->store('news', 'public');
                }

                News::create([
                    'rw_id' => auth()->user()->rw->id,
                    'tenant_id' => null, // Explicitly null for RW
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul).'-'.uniqid(),
                    'kategori' => $request->kategori,
                    'isi' => $request->isi,
                    'gambar' => $path,
                    'is_penting' => $request->boolean('is_penting'),
                ]);
            });

            return redirect()->route('rw.berita.index')->with('success', 'Berita RW berhasil dipublikasikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mempublikasikan berita: ' . $e->getMessage());
        }
    }

    public function edit(News $news)
    {
        if ($news->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }
        return view('rw.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        if ($news->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string',
            'kategori' => 'required|string',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
            'is_penting' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $news) {
                $path = $news->gambar;
                
                if ($request->hasFile('gambar')) {
                    if ($path) {
                        Storage::disk('public')->delete($path);
                    }
                    $path = $request->file('gambar')->store('news', 'public');
                }

                $news->update([
                    'judul' => $request->judul,
                    'kategori' => $request->kategori,
                    'isi' => $request->isi,
                    'gambar' => $path,
                    'is_penting' => $request->boolean('is_penting'),
                ]);
            });

            return redirect()->route('rw.berita.index')->with('success', 'Berita RW berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui berita: ' . $e->getMessage());
        }
    }

    public function destroy(News $news)
    {
        if ($news->rw_id !== auth()->user()->rw->id) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($news) {
                if ($news->gambar) {
                    Storage::disk('public')->delete($news->gambar);
                }
                
                $news->delete();
            });

            return back()->with('success', 'Berita RW berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus berita: ' . $e->getMessage());
        }
    }
}
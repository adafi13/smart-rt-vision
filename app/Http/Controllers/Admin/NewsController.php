<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::latest();

        if ($search = $request->input('search')) {
            $query->where('judul', 'like', "%{$search}%");
        }

        $newsList = $query->paginate(10)->withQueryString();

        return view('admin.news.index', ['newsList' => $newsList]);
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

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('news', 'public');
        }

        News::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul).'-'.uniqid(),
            'kategori' => $request->kategori,
            'isi' => $request->isi,
            'gambar' => $path,
            'is_penting' => $request->boolean('is_penting'),
        ]);

        return back()->with('success', 'Berita berhasil dipublikasikan.');
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'judul' => 'required|string',
            'kategori' => 'required|string',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
            'is_penting' => 'nullable|boolean',
        ]);

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

        return back()->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return back()->with('success', 'Berita dihapus.');
    }
}

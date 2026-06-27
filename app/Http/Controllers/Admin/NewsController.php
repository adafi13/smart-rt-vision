<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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

        try {
            DB::transaction(function () use ($request) {
                $path = null;
                if ($request->hasFile('gambar')) {
                    $path = $request->file('gambar')->store('news', 'public');
                }

                $news = News::create([
                    'tenant_id' => auth()->user()->tenant_id,
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul).'-'.uniqid(),
                    'kategori' => $request->kategori,
                    'isi' => $request->isi,
                    'gambar' => $path,
                    'is_penting' => $request->boolean('is_penting'),
                ]);

                AuditLog::create([
                    'tenant_id' => auth()->user()->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'create_news',
                    'model_type' => News::class,
                    'model_id' => $news->id,
                    'new_values' => $news->toArray(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Berita berhasil dipublikasikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mempublikasikan berita: ' . $e->getMessage());
        }
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

        try {
            DB::transaction(function () use ($request, $news) {
                $oldValues = $news->only('judul', 'kategori', 'isi', 'gambar', 'is_penting');
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

                AuditLog::create([
                    'tenant_id' => $news->tenant_id ?? auth()->user()->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'update_news',
                    'model_type' => News::class,
                    'model_id' => $news->id,
                    'old_values' => $oldValues,
                    'new_values' => $news->only('judul', 'kategori', 'isi', 'gambar', 'is_penting'),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Berita berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui berita: ' . $e->getMessage());
        }
    }

    public function destroy(News $news)
    {
        try {
            DB::transaction(function () use ($news) {
                $oldValues = $news->toArray();
                
                if ($news->gambar) {
                    Storage::disk('public')->delete($news->gambar);
                }
                
                $news->delete();

                AuditLog::create([
                    'tenant_id' => $news->tenant_id ?? auth()->user()->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'delete_news',
                    'model_type' => News::class,
                    'model_id' => $news->id,
                    'old_values' => $oldValues,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Berita dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus berita: ' . $e->getMessage());
        }
    }
}

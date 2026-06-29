<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDocumentController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $documents = Document::where('tenant_id', $tenantId)
            ->latest()
            ->paginate(15);
            
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:sk,notulen,laporan,surat_masuk,surat_keluar,umum',
            'file' => 'required|file|max:10240', // Max 10MB
            'is_public' => 'boolean',
        ]);

        $filePath = $request->file('file')->store('documents/' . auth()->user()->tenant_id, 'public');

        Document::create([
            'tenant_id' => auth()->user()->tenant_id,
            'uploaded_by' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'category' => $request->category,
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function edit(Document $document)
    {
        if ($document->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        if ($document->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:sk,notulen,laporan,surat_masuk,surat_keluar,umum',
            'file' => 'nullable|file|max:10240',
            'is_public' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $filePath = $request->file('file')->store('documents/' . auth()->user()->tenant_id, 'public');
            $document->file_path = $filePath;
        }

        $document->title = $request->title;
        $document->description = $request->description;
        $document->category = $request->category;
        $document->is_public = $request->has('is_public');
        $document->save();

        return redirect()->route('admin.documents.index')->with('success', 'Detail dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        if ($document->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(Document $document)
    {
        if ($document->tenant_id !== auth()->user()->tenant_id && !$document->is_public) {
            abort(403);
        }

        return Storage::disk('public')->download($document->file_path, $document->title);
    }
}

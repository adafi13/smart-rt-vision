<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;

class LetterRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterRequest::with('member.family')->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $letterRequests = $query->paginate(15)->withQueryString();

        return view('admin.letter-requests.index', compact('letterRequests'));
    }

    public function update(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'status' => 'required|in:Pending,Disetujui,Ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $letterRequest->update($request->only(['status', 'catatan_admin']));

        return back()->with('success', 'Status permohonan surat diperbarui.');
    }

    public function destroy(LetterRequest $letterRequest)
    {
        $letterRequest->delete();

        return back()->with('success', 'Permohonan surat dihapus.');
    }
}

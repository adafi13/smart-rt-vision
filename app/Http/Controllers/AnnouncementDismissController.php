<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementDismissal;
use Illuminate\Http\Request;

class AnnouncementDismissController extends Controller
{
    public function dismiss(Request $request)
    {
        $request->validate(['announcement_id' => 'required|exists:announcements,id']);

        AnnouncementDismissal::firstOrCreate([
            'announcement_id' => $request->announcement_id,
            'user_id'         => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}

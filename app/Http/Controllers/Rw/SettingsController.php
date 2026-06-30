<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $rw = auth()->user()->rw;
        $rwSignature = \App\Models\Setting::get("rw_{$rw->id}_signature_path");
        $rwHeadName = \App\Models\Setting::get("rw_{$rw->id}_head_name");
        return view('rw.settings', compact('rw', 'rwSignature', 'rwHeadName'));
    }

    public function update(Request $request)
    {
        $rw = auth()->user()->rw;

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'address'  => ['required', 'string', 'max:500'],
            'city'     => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'head_name' => ['nullable', 'string', 'max:255'],
            'rw_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $rw->update($request->only('name', 'address', 'city', 'province'));
        
        if ($request->filled('head_name')) {
            \App\Models\Setting::set("rw_{$rw->id}_head_name", $request->head_name);
        }

        if ($request->hasFile('rw_signature')) {
            $oldSignature = \App\Models\Setting::get("rw_{$rw->id}_signature_path");
            if ($oldSignature) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldSignature);
            }

            $path = $request->file('rw_signature')->store('signatures', 'public');
            \App\Models\Setting::set("rw_{$rw->id}_signature_path", $path);
        } elseif ($request->filled('signature_data')) {
            $oldSignature = \App\Models\Setting::get("rw_{$rw->id}_signature_path");
            if ($oldSignature) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldSignature);
            }
            
            $base64Data = $request->signature_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $base64Data = base64_decode($base64Data);
                    $filename = 'signatures/' . uniqid() . '.' . $type;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $base64Data);
                    \App\Models\Setting::set("rw_{$rw->id}_signature_path", $filename);
                }
            }
        }

        return back()->with('success', 'Informasi organisasi RW berhasil diperbarui.');
    }
}

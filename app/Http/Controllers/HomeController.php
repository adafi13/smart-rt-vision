<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Family;
use App\Models\Member;
use App\Models\News;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $totalIuran = Contribution::sum('jumlah');
        $totalPengeluaran = Expense::sum('jumlah');

        $chartData = Expense::select('kategori', DB::raw('sum(jumlah) as total'))
            ->groupBy('kategori')
            ->get();

        $stats = [
            'tenant' => app('currentTenant'),
            'total_warga' => Member::where('status_warga', 'Aktif')->count(),
            'total_kk' => Family::count(),
            'laki_laki' => Member::where('status_warga', 'Aktif')->where('jenis_kelamin', 'Laki-laki')->count(),
            'perempuan' => Member::where('status_warga', 'Aktif')->where('jenis_kelamin', 'Perempuan')->count(),

            'warta' => News::orderBy('created_at', 'desc')->take(3)->get(),
            'produk' => Product::where('is_ready', true)->orderBy('created_at', 'desc')->take(4)->get(),

            'total_masuk' => $totalIuran,
            'total_keluar' => $totalPengeluaran,
            'saldo_kas' => $totalIuran - $totalPengeluaran,

            'chart_labels' => $chartData->pluck('kategori'),
            'chart_values' => $chartData->pluck('total'),
            
            'inventories' => \App\Models\Inventory::where('tenant_id', app('currentTenant')->id)->get(),
            
            'ronda_schedules' => \App\Models\RondaSchedule::with(['member', 'attendance'])
                ->where('tenant_id', app('currentTenant')->id)
                ->where('date', now()->format('Y-m-d'))
                ->get(),
                
            'rt_staffs' => \App\Models\RtStaff::where('tenant_id', app('currentTenant')->id)
                ->where('is_active', true)
                ->orderBy('order_level')
                ->get(),
        ];

        return view('public.home', $stats);
    }

    public function pinjamInventaris(\Illuminate\Http\Request $request)
    {
        $tenant = app('currentTenant');
        
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_contact' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'expected_return_date' => 'required|date|after_or_equal:today',
        ]);

        $inventory = \App\Models\Inventory::where('tenant_id', $tenant->id)->findOrFail($request->inventory_id);
        
        if ($inventory->available_quantity < $request->quantity) {
            return back()->with('error', 'Maaf, sisa barang tidak mencukupi untuk jumlah yang Anda pinjam.');
        }

        \App\Models\InventoryBorrowing::create([
            'tenant_id' => $tenant->id,
            'inventory_id' => $inventory->id,
            'borrower_name' => $request->borrower_name,
            'borrower_contact' => $request->borrower_contact,
            'quantity' => $request->quantity,
            'borrow_date' => now(),
            'expected_return_date' => $request->expected_return_date,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan pinjam barang berhasil dikirim! Silakan hubungi pengurus RT untuk konfirmasi/pengambilan barang.');
    }

    public function cekNik(\Illuminate\Http\Request $request)
    {
        $nik = $request->query('nik');
        $warga = $nik ? Member::where('nik', $nik)->first() : null;

        return response()->json([
            'found' => (bool) $warga,
            'nama' => $warga ? substr($warga->nama, 0, 2).str_repeat('*', max(0, strlen($warga->nama) - 4)).substr($warga->nama, -2) : null,
            'status' => $warga ? $warga->status_warga : null,
        ]);
    }

    public function privasi()
    {
        return view('public.privasi', ['tenant' => app('currentTenant')]);
    }

    public function syarat()
    {
        return view('public.syarat', ['tenant' => app('currentTenant')]);
    }

    public function absenRonda(\Illuminate\Http\Request $request)
    {
        $tenantId = app('currentTenant')->id;
        
        $request->validate([
            'nik' => 'required|numeric|exists:members,nik',
            'location' => 'required|string',
            'foto' => 'required|image|max:2048',
        ], [
            'nik.exists' => 'NIK tidak ditemukan di database warga.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $warga = Member::where('tenant_id', $tenantId)->where('nik', $request->nik)->first();
        if (!$warga) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak terdaftar di RT ini.',
            ]);
        }

        $schedule = \App\Models\RondaSchedule::where('tenant_id', $tenantId)
            ->where('member_id', $warga->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, nama Anda tidak masuk dalam jadwal ronda untuk malam ini.',
            ]);
        }

        if ($schedule->attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi ronda untuk malam ini.',
            ]);
        }

        $path = $request->file('foto')->store('ronda-attendances', 'public');

        \App\Models\RondaAttendance::create([
            'tenant_id' => $tenantId,
            'ronda_schedule_id' => $schedule->id,
            'member_id' => $warga->id,
            'clock_in' => now(),
            'location' => $request->location,
            'photo_path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih, absen ronda berhasil dicatat. Selamat bertugas!',
        ]);
    }

    public function triggerPanic(\Illuminate\Http\Request $request)
    {
        $tenant = app('currentTenant');
        $tenantId = $tenant->id;

        $request->validate([
            'reporter_name' => 'required|string|max:255',
            'reporter_contact' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:100',
        ]);

        \App\Models\PanicAlert::create([
            'tenant_id' => $tenantId,
            'reporter_name' => $request->reporter_name,
            'reporter_contact' => $request->reporter_contact,
            'location' => $request->location,
            'type' => $request->type,
            'status' => 'active',
        ]);
        
        $rtPhone = $tenant->phone;
        
        // Fallback: Jika nomor RT utama kosong, cari nomor pengurus (Ketua RT atau Keamanan)
        if (empty($rtPhone)) {
            $staff = \App\Models\RtStaff::where('tenant_id', $tenantId)
                        ->whereNotNull('phone')
                        ->where(function($q) {
                            $q->where('position', 'like', '%Ketua%')
                              ->orWhere('position', 'like', '%Keamanan%')
                              ->orWhere('position', 'like', '%Satpam%');
                        })
                        ->orderBy('order_level')
                        ->first();
            if ($staff) {
                $rtPhone = $staff->phone;
            }
        }
        
        $waUrl = null;
        
        if ($rtPhone) {
            $rtPhone = preg_replace('/[^0-9]/', '', $rtPhone);
            if (str_starts_with($rtPhone, '0')) {
                $rtPhone = '62' . substr($rtPhone, 1);
            }
            
            $msg = "[URGENT] LAPORAN DARURAT WARGA\n";
            $msg .= "--------------------------------------\n";
            $msg .= "Telah diterima laporan kondisi darurat melalui Sistem Portal Warga dengan rincian sebagai berikut:\n\n";
            $msg .= "*DATA PELAPOR*\n";
            $msg .= "Nama      : " . $request->reporter_name . "\n";
            $msg .= "Kontak    : " . $request->reporter_contact . "\n\n";
            $msg .= "*INFORMASI KEJADIAN*\n";
            $msg .= "Kategori  : " . strtoupper($request->type) . "\n";
            $msg .= "Lokasi    : " . $request->location . "\n\n";
            $msg .= "--------------------------------------\n";
            $msg .= "Dimohon kepada tim Keamanan/Pengurus RT untuk dapat segera menindaklanjuti laporan ini. Terima kasih.";
            
            $waUrl = "https://wa.me/" . $rtPhone . "?text=" . urlencode($msg);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan Darurat telah dikirim! ' . ($waUrl ? 'Anda akan dialihkan ke WhatsApp pengurus...' : 'Pengurus RT akan segera menindaklanjuti.'),
            'whatsapp_url' => $waUrl
        ]);
    }
}

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
                ->whereDate('date', now()->format('Y-m-d'))
                ->get(),
                
            'rt_staffs' => \App\Models\RtStaff::where('tenant_id', app('currentTenant')->id)
                ->where('is_active', true)
                ->orderBy('order_level')
                ->get(),
                
            'active_polls' => \App\Models\Poll::with('options')
                ->where('tenant_id', app('currentTenant')->id)
                ->get()
                ->filter(function($poll) { return $poll->is_active; }),
                
            'agendas' => \App\Models\Agenda::where('tenant_id', app('currentTenant')->id)
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                ->take(5)
                ->get(),
                
            'public_documents' => \App\Models\Document::where('tenant_id', app('currentTenant')->id)
                ->where('is_public', true)
                ->latest()
                ->take(10)
                ->get(),
                
            'active_cctvs' => \App\Models\Cctv::where('tenant_id', app('currentTenant')->id)
                ->where('status', 'active')
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
            ->whereDate('date', now()->format('Y-m-d'))
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
    public function submitVote(\Illuminate\Http\Request $request)
    {
        $tenantId = app('currentTenant')->id;

        $request->validate([
            'nik' => 'required|string',
            'poll_id' => 'required|exists:polls,id',
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        // Cek validasi NIK aktif
        $member = \App\Models\Member::where('tenant_id', $tenantId)
                    ->where('nik', $request->nik)
                    ->where('status_warga', 'Aktif')
                    ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: NIK tidak ditemukan atau status warga tidak aktif.'
            ]);
        }

        // Cek apakah poll valid dan aktif
        $poll = \App\Models\Poll::where('id', $request->poll_id)->where('tenant_id', $tenantId)->first();
        if (!$poll || !$poll->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: Polling tidak ditemukan atau sudah ditutup.'
            ]);
        }

        // Cek apakah sudah pernah vote
        $alreadyVoted = \App\Models\PollVote::where('poll_id', $poll->id)
                            ->where('nik', $request->nik)
                            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: Anda (NIK ini) sudah pernah memberikan suara pada polling ini. 1 Warga = 1 Suara.'
            ]);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $poll) {
                \App\Models\PollVote::create([
                    'poll_id' => $poll->id,
                    'poll_option_id' => $request->poll_option_id,
                    'nik' => $request->nik,
                ]);

                \App\Models\PollOption::where('id', $request->poll_option_id)->increment('votes_count');
            });

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Suara Anda berhasil direkam.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    public function submitGuestbook(\Illuminate\Http\Request $request)
    {
        $tenantId = app('currentTenant')->id;

        $request->validate([
            'pin' => 'required|string',
            'nama_tamu' => 'required|string|max:255',
            'plat_nomor' => 'nullable|string|max:20',
            'tujuan_rumah' => 'required|string|max:255',
            'keperluan' => 'required|string',
        ]);

        // Verifikasi PIN dengan pengaturan (fallback ke PIN default '1234' jika belum diatur)
        $securityPin = \App\Models\Setting::get("tenant_{$tenantId}_security_pin", "1234");
        
        if ($request->pin !== $securityPin) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: PIN Keamanan salah!'
            ]);
        }

        try {
            \App\Models\Guestbook::create([
                'tenant_id' => $tenantId,
                'nama_tamu' => $request->nama_tamu,
                'plat_nomor' => strtoupper($request->plat_nomor),
                'tujuan_rumah' => $request->tujuan_rumah,
                'keperluan' => $request->keperluan,
                'status' => 'Masuk',
                'waktu_masuk' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data tamu berhasil dicatat!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem.'
            ]);
        }
    }

    public function storeKos(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'alamat_kos' => 'required|string|max:255',
            'nama_penghuni' => 'required|string|max:255',
            'nik_penghuni' => 'required|string|size:16',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $tenantId = app('currentTenant')->id;

        $fotoKtpPath = $request->file('foto_ktp')->store('warga-kos/ktp', 'public');

        \App\Models\TemporaryResident::create([
            'tenant_id' => $tenantId,
            'nama_pemilik' => $request->nama_pemilik,
            'alamat_kos' => $request->alamat_kos,
            'nama_penghuni' => $request->nama_penghuni,
            'nik_penghuni' => $request->nik_penghuni,
            'foto_ktp' => $fotoKtpPath,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Laporan tamu menginap/kos berhasil dikirim! Silakan tunggu verifikasi pengurus RT.');
    }
}

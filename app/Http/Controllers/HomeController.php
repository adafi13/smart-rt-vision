<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Expense;
use App\Models\Family;
use App\Models\Member;
use App\Models\News;
use App\Models\Product;
use App\Models\VacantHome;
use Illuminate\Http\Request;
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

            'warta' => News::where('tenant_id', app('currentTenant')->id)
                ->when(app('currentTenant')->rw_id, function ($query) {
                    $query->orWhere(function ($q) {
                        $q->whereNull('tenant_id')->where('rw_id', app('currentTenant')->rw_id);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get(),
            'produk' => Product::where('is_ready', true)->orderBy('created_at', 'desc')->take(4)->get(),

            'total_masuk' => $totalIuran,
            'total_keluar' => $totalPengeluaran,
            'saldo_kas' => $totalIuran - $totalPengeluaran,

            'chart_labels' => $chartData->pluck('kategori'),
            'chart_values' => $chartData->pluck('total'),
            
            'rw_broadcasts' => app('currentTenant')->rw_id ? \App\Models\RwBroadcast::where('rw_id', app('currentTenant')->rw_id)
                ->where('status', 'active')
                ->latest()
                ->take(3)
                ->get() : collect(),
            
            'inventories' => \App\Models\Inventory::where('tenant_id', app('currentTenant')->id)
                ->when(app('currentTenant')->rw_id, function ($query) {
                    $query->orWhere(function ($q) {
                        $q->whereNull('tenant_id')->where('rw_id', app('currentTenant')->rw_id);
                    });
                })
                ->get(),
            
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
                
            'agendas' => \App\Models\Agenda::where(function($query) {
                    $query->where('tenant_id', app('currentTenant')->id)
                          ->when(app('currentTenant')->rw_id, function ($q) {
                              $q->orWhere(function ($subQ) {
                                  $subQ->whereNull('tenant_id')->where('rw_id', app('currentTenant')->rw_id);
                              });
                          });
                })
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
        $tenantId = app('currentTenant')->id;
        
        $warga = $nik ? Member::where('nik', $nik)
            ->whereHas('family', function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->first() : null;

        if (!$warga) {
            return response()->json([
                'found' => false,
            ]);
        }

        $family = $warga->family;
        $statusVerifikasi = $family->status_verifikasi ?? 'pending';
        $namaMasked = substr($warga->nama, 0, 2).str_repeat('*', max(0, strlen($warga->nama) - 4)).substr($warga->nama, -2);

        if ($statusVerifikasi === 'pending') {
            return response()->json([
                'found' => true,
                'nama' => $namaMasked,
                'status' => 'Pending',
                'message' => 'Pendaftaran KK dengan NIK ini sedang MENUNGGU PERSETUJUAN Ketua RT.'
            ]);
        }

        if ($statusVerifikasi === 'ditolak') {
            return response()->json([
                'found' => true,
                'nama' => $namaMasked,
                'status' => 'Ditolak',
                'message' => 'Pendaftaran KK Anda DITOLAK oleh Ketua RT. Alasan: ' . ($family->alasan_penolakan ?? '-')
            ]);
        }

        return response()->json([
            'found' => true,
            'nama' => $namaMasked,
            'status' => $warga->status_warga ?? 'Aktif',
            'message' => 'Warga terdaftar aktif di RT ini.'
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
        
        try {
            $admins = \App\Models\User::where('tenant_id', $tenantId)
                ->whereIn('role', ['admin', 'super_admin'])
                ->get();
                
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\EmergencyAlert(
                "🚨 DARURAT: " . strtoupper($request->type),
                "Dari {$request->reporter_name} di {$request->location}. Segera cek sekarang!",
                "/admin/panic-alerts"
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WebPush Error: ' . $e->getMessage());
        }
        
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

        // Cek umur wajib 17 tahun ke atas
        if (!$member->tanggal_lahir) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: Data tanggal lahir NIK Anda belum terdaftar. Hubungi pengurus RT untuk melengkapi data.'
            ]);
        }

        $birthDate = \Carbon\Carbon::parse($member->tanggal_lahir);
        if ($birthDate->age < 17) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: Anda belum memenuhi syarat usia untuk mengikuti voting (minimal 17 tahun).'
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

            $options = $poll->options()->get();
            $totalVotes = $options->sum('votes_count');
            
            $formattedOptions = $options->map(function($opt) use ($totalVotes) {
                return [
                    'id' => $opt->id,
                    'text' => $opt->option_text,
                    'votes' => $opt->votes_count,
                    'percentage' => $totalVotes > 0 ? round(($opt->votes_count / $totalVotes) * 100, 1) : 0,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Suara Anda berhasil direkam.',
                'total_votes' => $totalVotes,
                'options' => $formattedOptions,
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

        return response()->json([
            'success' => true,
            'message' => 'Laporan tamu menginap/kos berhasil dikirim! Silakan tunggu verifikasi pengurus RT.'
        ]);
    }

    public function storeVacantHome(Request $request)
    {
        $tenantId = request()->route('tenant')->id;
        
        $request->validate([
            'pelapor_nama' => 'required|string|max:255',
            'alamat_rumah' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
            'tanggal_pergi' => 'required|date',
            'tanggal_pulang' => 'required|date|after_or_equal:tanggal_pergi',
            'catatan_warga' => 'nullable|string',
        ]);

        VacantHome::create([
            'tenant_id' => $tenantId,
            'pelapor_nama' => $request->pelapor_nama,
            'alamat_rumah' => $request->alamat_rumah,
            'nomor_wa' => $request->nomor_wa,
            'tanggal_pergi' => $request->tanggal_pergi,
            'tanggal_pulang' => $request->tanggal_pulang,
            'catatan_warga' => $request->catatan_warga,
            'status' => 'Aktif',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan rumah kosong berhasil dikirim. Pengurus/Keamanan akan memantau rumah Anda.'
        ]);
    }

    public function trackVacantHome(Request $request)
    {
        $tenantId = app('currentTenant')->id;
        $nomorWa = $request->query('nomor_wa');

        if (!$nomorWa) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor WA Anda.'
            ]);
        }

        $vacantHome = VacantHome::where('tenant_id', $tenantId)
            ->where('nomor_wa', $nomorWa)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$vacantHome) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan laporan penitipan rumah untuk nomor WA tersebut.'
            ]);
        }

        $timeline = [];
        
        // Add creation event
        $timeline[] = [
            'is_system' => true,
            'pesan' => 'Rumah mulai dipantau oleh pengurus / keamanan.',
            'waktu' => $vacantHome->created_at->format('d M Y, H:i'),
        ];

        // Add patrol logs
        $logs = $vacantHome->logs()->orderBy('waktu_patroli', 'asc')->get();
        foreach ($logs as $log) {
            $timeline[] = [
                'is_system' => false,
                'is_admin' => true, // Make it look like admin reply
                'sender' => 'Satpam: ' . $log->petugas_nama,
                'pesan' => $log->catatan_petugas ?? 'Patroli rutin (Tidak ada catatan)',
                'waktu' => $log->waktu_patroli->format('d M Y, H:i'),
                'attachment_url' => $log->foto_bukti ? \Illuminate\Support\Facades\Storage::url($log->foto_bukti) : null,
            ];
        }

        if ($vacantHome->status === 'Selesai') {
            $timeline[] = [
                'is_system' => true,
                'pesan' => 'Masa penitipan telah selesai.',
                'waktu' => $vacantHome->updated_at->format('d M Y, H:i'),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $vacantHome->status,
                'ticket_number' => 'VH-' . $vacantHome->id,
                'no_reply_form' => true, // We will use this flag in JS to hide the reply form
                'timeline' => $timeline
            ]
        ]);
    }

    public function wargaUploadKk()
    {
        $tenant = app('currentTenant');
        return view('public.kk.upload', compact('tenant'));
    }

    public function wargaExtractKk(Request $request)
    {
        $tenant = app('currentTenant');
        
        // 1. Cek Kuota KK
        if (!$tenant->canAddMoreKk()) {
            return back()->with('error', 'Jumlah Kartu Keluarga di RT ini sudah mencapai batas paket. Silakan hubungi Ketua RT untuk peningkatan paket.');
        }

        // 1b. Cek Kuota AI
        if (! $tenant->canRunAiExtraction()) {
            return back()->with('error', 'Kuota ekstraksi AI bulan ini sudah habis untuk RT ini. Silakan hubungi Ketua RT.');
        }

        // 2. Validasi File
        $request->validate([
            'foto_kk' => 'required|mimes:jpeg,png,jpg,pdf|max:8192',
        ]);

        $path = $request->file('foto_kk')->store('kk_images', 'public');

        // Increment count since we start extraction
        $tenant->incrementAiUsage();

        try {
            $extractor = new \App\Services\KkExtractor();
            $result = $extractor->extract(storage_path('app/public/' . $path));

            if (!$result) {
                $tenant->decrement('ai_extractions_used');
                \App\Models\KkScanLog::create([
                    'tenant_id' => $tenant->id,
                    'uploader_type' => 'warga',
                    'file_path' => $path,
                    'status' => 'failed',
                    'error_message' => 'Layanan AI sedang sibuk atau batas kuota API habis.',
                ]);
                return back()->with('error', 'Gagal memproses dokumen KK: Layanan AI sedang sibuk atau batas kuota API habis. Silakan coba lagi beberapa saat lagi.')->withInput();
            }

            if (isset($result['error'])) {
                // Decrement if failed due to invalid image
                $tenant->decrement('ai_extractions_used');
                \App\Models\KkScanLog::create([
                    'tenant_id' => $tenant->id,
                    'uploader_type' => 'warga',
                    'file_path' => $path,
                    'status' => 'failed',
                    'error_message' => $result['error'],
                ]);
                return back()->with('error', $result['error'])->withInput();
            }

            // Fallback: Cari nama kepala keluarga dari anggota jika kosong di root
            if (empty($result['nama_kepala_keluarga']) && isset($result['anggota']) && is_array($result['anggota'])) {
                foreach ($result['anggota'] as $anggota) {
                    $hub = strtolower($anggota['hubungan_keluarga'] ?? '');
                    if ($hub === 'kepala keluarga' || $hub === 'suami') {
                        $result['nama_kepala_keluarga'] = $anggota['nama'] ?? '';
                        break;
                    }
                }
            }

            \App\Models\KkScanLog::create([
                'tenant_id' => $tenant->id,
                'uploader_type' => 'warga',
                'file_path' => $path,
                'status' => 'success',
                'nomor_kk' => $result['nomor_kk'] ?? null,
                'nama_kepala_keluarga' => $result['nama_kepala_keluarga'] ?? null,
            ]);

            session([
                'warga_kk_extracted' => $result,
                'warga_kk_foto_path' => $path
            ]);

            return redirect()->route('warga.kk.verify', ['tenant' => $tenant->slug]);
        } catch (\Exception $e) {
            $tenant->decrement('ai_extractions_used');
            \App\Models\KkScanLog::create([
                'tenant_id' => $tenant->id,
                'uploader_type' => 'warga',
                'file_path' => $path,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Gagal memproses dokumen KK: ' . $e->getMessage())->withInput();
        }
    }

    public function wargaVerifyKk()
    {
        if (!session()->has('warga_kk_extracted')) {
            $tenant = app('currentTenant');
            return redirect()->route('warga.kk.upload', ['tenant' => $tenant->slug]);
        }

        $tenant = app('currentTenant');
        $data = session('warga_kk_extracted');
        $fotoPath = session('warga_kk_foto_path');

        $warningsKk = \App\Services\NikValidator::validateKk($data['nomor_kk'] ?? '');
        $warningsAnggota = [];

        // 1. Dapatkan Tahun Lahir Kepala Keluarga (KK)
        $parentBirthYear = null;
        if (isset($data['anggota']) && is_array($data['anggota'])) {
            foreach ($data['anggota'] as $anggota) {
                $hubungan = strtolower($anggota['hubungan_keluarga'] ?? '');
                if (($hubungan === 'kepala keluarga' || $hubungan === 'suami') && !empty($anggota['tanggal_lahir'])) {
                    $parentBirthYear = (int)date('Y', strtotime($anggota['tanggal_lahir']));
                    break;
                }
            }
        }

        // 2. Jalankan validasi dasar & validasi relasi umur orang tua-anak
        if (isset($data['anggota']) && is_array($data['anggota'])) {
            foreach ($data['anggota'] as $i => $anggota) {
                $warningsAnggota[$i] = \App\Services\NikValidator::validate($anggota);

                $hubungan = strtolower($anggota['hubungan_keluarga'] ?? '');
                if ($hubungan === 'anak' && !empty($anggota['tanggal_lahir']) && $parentBirthYear !== null) {
                    $childBirthYear = (int)date('Y', strtotime($anggota['tanggal_lahir']));
                    // Jika anak lahir sebelum orang tua atau selisih umur kurang dari 14 tahun
                    if ($childBirthYear <= $parentBirthYear || ($childBirthYear - $parentBirthYear) < 14) {
                        $warningsAnggota[$i]['tanggal_lahir'][] = 'Peringatan: Tahun lahir anak (' . $childBirthYear . ') tidak selaras dengan Kepala Keluarga (' . $parentBirthYear . ').';
                    }
                }
            }
        }

        return view('public.kk.verify', compact('tenant', 'data', 'fotoPath', 'warningsKk', 'warningsAnggota'));
    }

    public function wargaStoreKk(Request $request)
    {
        $tenant = app('currentTenant');

        // 1. Cek Kuota
        if (!$tenant->canAddMoreKk()) {
            return redirect()->route('warga.kk.upload', ['tenant' => $tenant->slug])
                ->with('error', 'Jumlah Kartu Keluarga di RT ini sudah mencapai batas paket. Silakan hubungi Ketua RT.');
        }

        // 2. Validasi Input
        $request->validate([
            'nomor_kk' => 'required|string|size:16',
            'nama_kepala_keluarga' => 'required|string',
        ]);

        $tenantId = $tenant->id;

        // 3. Cek apakah Nomor KK sudah ada (Pendaftaran vs Pembaruan)
        $existingFamily = \App\Models\Family::where('tenant_id', $tenantId)
            ->where('nomor_kk', $request->nomor_kk)
            ->first();

        if ($existingFamily) {
            // Ini adalah request Pembaruan KK (Update)
            // Pastikan tidak ada request update yang masih pending
            $hasPendingUpdate = \App\Models\KkUpdateRequest::where('family_id', $existingFamily->id)
                ->where('status', 'pending')
                ->exists();

            if ($hasPendingUpdate) {
                return back()->with('error', 'Gagal: Anda sudah memiliki permohonan pembaruan KK yang sedang menunggu persetujuan Ketua RT.')->withInput();
            }

            // Validasi: Cegah pembaruan jika data sama persis (mencegah spam ke RT)
            $isIdentical = true;
            $incomingAnggota = $request->anggota ?? [];
            
            if (count($incomingAnggota) !== $existingFamily->members()->count()) {
                $isIdentical = false;
            } else {
                foreach ($incomingAnggota as $anggotaData) {
                    if (empty($anggotaData['nik'])) continue;
                    
                    $member = $existingFamily->members()->where('nik', $anggotaData['nik'])->first();
                    if (!$member) {
                        $isIdentical = false;
                        break;
                    }

                    // Bandingkan field yang rentan berubah. Gunakan strtolower & trim untuk menoleransi sedikit typo AI
                    $newStatus = strtolower(trim($anggotaData['status_perkawinan'] ?? ''));
                    $oldStatus = strtolower(trim($member->status_perkawinan ?? ''));
                    $newPekerjaan = strtolower(trim($anggotaData['pekerjaan'] ?? ''));
                    $oldPekerjaan = strtolower(trim($member->pekerjaan ?? ''));
                    $newPendidikan = strtolower(trim($anggotaData['pendidikan'] ?? ''));
                    $oldPendidikan = strtolower(trim($member->pendidikan ?? ''));

                    if ($newStatus !== $oldStatus || $newPekerjaan !== $oldPekerjaan || $newPendidikan !== $oldPendidikan) {
                        $isIdentical = false;
                        break;
                    }
                }
            }

            if ($isIdentical) {
                return back()->with('error', 'Gagal: Tidak terdeteksi adanya perubahan data (jumlah anggota, NIK, status perkawinan, maupun pekerjaan) pada KK yang Anda unggah dengan data kami saat ini. Pembaruan tidak diperlukan.')->withInput();
            }

            try {
                \App\Models\KkUpdateRequest::create([
                    'tenant_id' => $tenantId,
                    'family_id' => $existingFamily->id,
                    'data' => $request->all(),
                    'foto_path' => $request->foto_path ?? session('warga_kk_foto_path'),
                    'status' => 'pending',
                ]);

                \App\Models\AuditLog::create([
                    'tenant_id' => $tenantId,
                    'user_id' => null,
                    'action' => 'citizen_submit_family_update',
                    'model_type' => \App\Models\Family::class,
                    'model_id' => $existingFamily->id,
                    'new_values' => ['nomor_kk' => $request->nomor_kk],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                session()->forget(['warga_kk_extracted', 'warga_kk_foto_path']);
                
                return redirect()->route('home', ['tenant' => $tenant->slug])
                    ->with('success', 'Data KK Anda sudah terdaftar. Permohonan pembaruan data KK Anda telah dikirim ke Ketua RT untuk disetujui.');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengirim permohonan pembaruan KK: ' . $e->getMessage())->withInput();
            }
        }

        // 4. Validasi Keunikan NIK Anggota pada RT ini (Untuk Keluarga Baru)
        if ($request->has('anggota') && is_array($request->anggota)) {
            foreach ($request->anggota as $anggotaData) {
                if (empty($anggotaData['nik'])) continue;
                
                $existsNik = \App\Models\Member::whereHas('family', function ($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->where('nik', $anggotaData['nik'])->exists();
                
                if ($existsNik) {
                    return back()->with('error', 'Gagal: Warga dengan NIK ' . $anggotaData['nik'] . ' (' . ($anggotaData['nama'] ?? 'Tanpa Nama') . ') sudah terdaftar di database RT.')->withInput();
                }
            }
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $tenantId) {
                $family = \App\Models\Family::create([
                    'tenant_id' => $tenantId,
                    'nomor_kk' => $request->nomor_kk,
                    'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
                    'alamat' => $request->alamat ?? '',
                    'rt' => $request->rt ?? '',
                    'rw' => $request->rw ?? '',
                    'desa_kelurahan' => $request->desa_kelurahan ?? '',
                    'kecamatan' => $request->kecamatan ?? '',
                    'kabupaten_kota' => $request->kabupaten_kota ?? '',
                    'provinsi' => $request->provinsi ?? '',
                    'kode_pos' => $request->kode_pos ?? '',
                    'foto_path' => $request->foto_path ?? null,
                    'status_verifikasi' => 'pending', // Menunggu persetujuan
                ]);

                if ($request->has('anggota') && is_array($request->anggota)) {
                    foreach ($request->anggota as $anggotaData) {
                        if (empty($anggotaData['nik']) || empty($anggotaData['nama'])) continue;

                        $family->members()->create([
                            'nik' => $anggotaData['nik'],
                            'nama' => $anggotaData['nama'],
                            'jenis_kelamin' => $anggotaData['jenis_kelamin'] ?? 'Laki-laki',
                            'tempat_lahir' => $anggotaData['tempat_lahir'] ?? null,
                            'tanggal_lahir' => $anggotaData['tanggal_lahir'] ?? null,
                            'agama' => $anggotaData['agama'] ?? null,
                            'pendidikan' => $anggotaData['pendidikan'] ?? null,
                            'pekerjaan' => $anggotaData['pekerjaan'] ?? null,
                            'status_perkawinan' => $anggotaData['status_perkawinan'] ?? null,
                            'hubungan_keluarga' => $anggotaData['hubungan_keluarga'] ?? 'Anggota Keluarga',
                            'kewarganegaraan' => $anggotaData['kewarganegaraan'] ?? 'WNI',
                            'nama_ayah' => $anggotaData['nama_ayah'] ?? null,
                            'nama_ibu' => $anggotaData['nama_ibu'] ?? null,
                        ]);
                    }
                }

                \App\Models\AuditLog::create([
                    'tenant_id' => $tenantId,
                    'user_id' => null, // Warga mandiri tanpa user login
                    'action' => 'citizen_submit_family',
                    'model_type' => \App\Models\Family::class,
                    'model_id' => $family->id,
                    'new_values' => ['nomor_kk' => $family->nomor_kk, 'nama' => $family->nama_kepala_keluarga],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            session()->forget(['warga_kk_extracted', 'warga_kk_foto_path']);
            
            return redirect()->route('home', ['tenant' => $tenant->slug])
                ->with('success', 'Data pendaftaran KK Anda berhasil dikirim ke Ketua RT. Mohon tunggu proses persetujuan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim pendaftaran KK: ' . $e->getMessage())->withInput();
        }
    }
}

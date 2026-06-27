<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun Super Admin baru dengan cepat (aman untuk hosting)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('--- Pembuatan Akun Super Admin ---');
        
        $name = $this->ask('Masukkan Nama Lengkap', 'Super Admin');
        $email = $this->ask('Masukkan Email', 'admin@sekawanputrapratama.com');
        $password = $this->secret('Masukkan Password (minimal 8 karakter)');
        
        if (strlen($password) < 8) {
            $this->error('Password harus minimal 8 karakter!');
            return;
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $this->warn("Email {$email} sudah terdaftar!");
            if ($this->confirm('Apakah Anda ingin menimpa password-nya?')) {
                $user->password = Hash::make($password);
                $user->save();
                $this->info('Password berhasil direset!');
            }
            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            // Jika ada column role, tambahkan di sini (opsional tergantung struktur DB Anda)
        ]);

        $this->info("Akun Super Admin ({$email}) berhasil dibuat! Silakan login.");
    }
}

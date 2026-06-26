<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Dijalankan manual oleh tim PT Sekawan Putra Pratama untuk membuat akun
 * Super Admin pertama: php artisan app:make-super-admin
 * Sengaja interaktif (tanya email & password) — bukan dibuat otomatis oleh AI,
 * supaya kredensial akun berhak-penuh ini sepenuhnya ditentukan oleh Anda.
 */
#[Signature('app:make-super-admin')]
#[Description('Buat (atau jadikan) akun Super Admin — punya akses ke Panel Super Admin, tidak terikat tenant manapun.')]
class MakeSuperAdmin extends Command
{
    public function handle(): int
    {
        $email = $this->ask('Email akun Super Admin');

        $validator = Validator::make(['email' => $email], ['email' => 'required|email']);
        if ($validator->fails()) {
            $this->error('Email tidak valid.');

            return self::FAILURE;
        }

        $existing = User::where('email', $email)->first();

        if ($existing) {
            if (! $this->confirm("User dengan email {$email} sudah ada (tenant_id: ".($existing->tenant_id ?? 'tidak ada').'). Jadikan Super Admin? Ini akan melepas user dari tenant-nya jika ada.')) {
                return self::SUCCESS;
            }

            $existing->forceFill(['is_super_admin' => true, 'tenant_id' => null])->save();
            $this->info("User {$email} sekarang adalah Super Admin.");

            return self::SUCCESS;
        }

        $name = $this->ask('Nama lengkap');
        $password = $this->secret('Password (minimal 8 karakter)');

        $validator = Validator::make(['password' => $password], ['password' => 'required|min:8']);
        if ($validator->fails()) {
            $this->error('Password minimal 8 karakter.');

            return self::FAILURE;
        }

        User::forceCreate([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_super_admin' => true,
            'tenant_id' => null,
        ]);

        $this->info("Akun Super Admin {$email} berhasil dibuat. Login di /login, lalu akses /super-admin.");

        return self::SUCCESS;
    }
}

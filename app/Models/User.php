<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\BelongsToTenant;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'email', 'password', 'role', 'su_role', 'tenant_role', 'google_id', 'avatar', 'rw_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, BelongsToTenant, \NotificationChannels\WebPush\HasPushSubscriptions;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function isSuperAdminOwner(): bool
    {
        return $this->isSuperAdmin() && ($this->su_role === 'owner' || empty($this->su_role));
    }

    public function isSuperAdminFinance(): bool
    {
        return $this->isSuperAdmin() && $this->su_role === 'finance';
    }

    public function isSuperAdminSupport(): bool
    {
        return $this->isSuperAdmin() && $this->su_role === 'support';
    }

    public function isRtOwner(): bool
    {
        return $this->isAdminRt() && (empty($this->tenant_role) || in_array($this->tenant_role, ['owner', 'wakil_ketua']));
    }

    public function isRtSekretaris(): bool
    {
        return $this->isAdminRt() && $this->tenant_role === 'sekretaris';
    }

    public function isRtBendahara(): bool
    {
        return $this->isAdminRt() && $this->tenant_role === 'bendahara';
    }

    public function isAdminRt(): bool
    {
        return $this->role === 'admin_rt';
    }

    public function isAdminRw(): bool
    {
        return $this->role === 'admin_rw';
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    public function rw()
    {
        return $this->belongsTo(Rw::class, 'rw_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
}

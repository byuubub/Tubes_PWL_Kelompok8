<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'hospital_id', 'name', 'email', 'password', 'role',
        'phone', 'address', 'gender', 'date_of_birth', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke rumah sakit (nullable untuk super admin dan pasien)
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // Relasi one-to-one ke patient (hanya untuk role pasien)
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // Relasi one-to-one ke doctor (hanya untuk role dokter)
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // Relasi one-to-one ke staff (hanya untuk role staff)
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    // Helper untuk cek role
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdminRs()
    {
        return $this->role === 'admin_rs';
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isPasien()
    {
        return $this->role === 'pasien';
    }
}

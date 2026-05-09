<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'city', 'address', 'logo', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: satu rumah sakit memiliki banyak user (admin, dokter, staff)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi: satu rumah sakit memiliki banyak pasien (melalui patients)
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    // Relasi: satu rumah sakit memiliki banyak dokter (melalui users dan doctors)
    // Tapi karena doctors tidak punya hospital_id, kita perlu relasi melalui users
    public function doctors()
    {
        return $this->hasManyThrough(Doctor::class, User::class, 'hospital_id', 'user_id');
    }

    public function staff()
    {
        return $this->hasManyThrough(Staff::class, User::class, 'hospital_id', 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialization_id', 'licence_number',
        'consultation_fee', 'years_of_experience',
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'years_of_experience' => 'integer',
    ];

    protected static function booted()
    {
        static::addGlobalScope('hospital', function ($query) {
            if (auth()->check() && auth()->user()->hospital_id) {
                $query->whereHas('user', function ($q) {
                    $q->where('hospital_id', auth()->user()->hospital_id);
                });
            }
        });
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke spesialisasi
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    // Relasi ke schedules (jadwal)
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Relasi ke appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relasi ke medical records
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Akses untuk mendapatkan nama dokter dari user
    public function getNameAttribute()
    {
        return $this->user->name;
    }
}

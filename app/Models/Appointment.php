<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'scheduled_at', 'status', 'complaint',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'status' => 'string',
    ];

    protected static function booted()
    {
        static::addGlobalScope('hospital', function ($query) {
            if (auth()->check() && auth()->user()->hospital_id) {
                $query->whereHas('patient', function ($q) {
                    $q->where('hospital_id', auth()->user()->hospital_id);
                });
            }
        });
    }

    // Relasi ke pasien
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relasi ke dokter
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Relasi ke medical record (hanya satu per appointment)
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    // Relasi ke bill (bisa ada satu per appointment)
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    // Relasi ke queue (opsional, appointment bisa punya antrian)
    public function queue()
    {
        return $this->hasOne(Queue::class);
    }

    // Scope untuk appointment hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }
}

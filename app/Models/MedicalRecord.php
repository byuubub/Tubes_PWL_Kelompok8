<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_id', 'visit_date',
        'diagnosis', 'treatment_plan', 'notes', 'case_status',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'case_status' => 'string',
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

    // Relasi ke appointment (nullable)
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Relasi ke prescriptions
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // Helper untuk menandai sembuh
    public function heal()
    {
        $this->case_status = 'healed';
        $this->save();
    }
}

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

    // Relasi ke bill (opsional: bisa aja bill di-create dari medical record)
    // Karena bill lebih terikat ke appointment atau kunjungan, tapi kita tetap bisa
    public function bill()
    {
        return $this->hasOne(Bill::class, 'patient_id', 'patient_id') // perlu logika lebih rumit
            ->where('appointment_id', $this->appointment_id);
    }

    // Helper untuk menandai sembuh
    public function heal()
    {
        $this->case_status = 'healed';
        $this->save();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'queue_date', 'queue_number', 'type',
        'appointment_id', 'status', 'priority', 'called_at', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'queue_date' => 'date',
        'called_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'priority' => 'integer',
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

    // Relasi ke dokter (optional)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Relasi ke appointment (optional)
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Scope untuk antrian yang masih waiting
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    // Scope untuk hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('queue_date', today());
    }

    // Mengambil antrian berikutnya berdasarkan prioritas
    public static function getNextQueue($hospitalId = null)
    {
        $query = self::today()->where('status', 'waiting');
        if ($hospitalId) {
            $query->whereHas('patient', function ($q) use ($hospitalId) {
                $q->where('hospital_id', $hospitalId);
            });
        }

        return $query->orderBy('priority')->orderBy('queue_number')->first();
    }
}

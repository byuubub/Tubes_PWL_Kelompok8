<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'day_of_week', 'start_time', 'end_time',
        'max_patients', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'day_of_week' => 'integer',
    ];

    protected static function booted()
    {
        static::addGlobalScope('hospital', function ($query) {
            if (auth()->check() && auth()->user()->hospital_id) {
                $query->whereHas('doctor.user', function ($q) {
                    $q->where('hospital_id', auth()->user()->hospital_id);
                });
            }
        });
    }

    // Relasi ke dokter
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

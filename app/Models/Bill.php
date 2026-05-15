<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'appointment_id', 'total_amount', 'status', 'payment_due_date',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'status' => 'string',
        'payment_due_date' => 'date',
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

    // Relasi ke appointment (nullable)
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Relasi ke payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Hitung total yang sudah dibayar
    public function getPaidAmountAttribute()
    {
        return $this->payments->sum('amount');
    }

    // Cek apakah lunas
    public function isPaid()
    {
        return $this->status === 'paid';
    }
}

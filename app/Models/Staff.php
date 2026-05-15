<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'position', 'department'];

    // app/Models/Staff.php
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
}

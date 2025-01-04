<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'recommended_major', 'score'];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Relasi ke model Answer (untuk mendapatkan semua jawaban mahasiswa)
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}

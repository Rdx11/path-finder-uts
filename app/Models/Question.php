<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['criteria_id', 'question'];

    /**
     * Relasi ke model Criteria
     */
    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    /**
     * Relasi ke model Answer
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}

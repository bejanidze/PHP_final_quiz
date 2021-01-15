<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cover',
        'description',
        'status',
        'index',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function inCompletedAnswers()
    {
        return $this->hasMany(Question::class)->doesntHave('user_answer');
    }
}

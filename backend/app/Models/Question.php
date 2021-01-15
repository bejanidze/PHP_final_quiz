<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
            'title',
            'description',
            'quiz_id'
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function user_answer()
    {
        return $this->hasOne(UserAnswer::class)->where('user_id','=',Auth::user()->id);
    }
}

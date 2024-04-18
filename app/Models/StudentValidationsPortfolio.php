<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentValidationsPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_portfolio_path',
    ];

    public function portfolio_student()
    {
        return $this->belongsTo(StudentValidation::class, 'user_id', 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'norsu_id_number',
        'course',
        'area_of_expertise',
        'year_level',
        'front_id',
        'about_me',
        'back_id',
        'is_student',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentSkills()
    {
        return $this->hasMany(StudentSkills::class, 'user_id', 'user_id');
    }

    public function studentPortfolio()
    {
        return $this->hasMany(StudentValidationsPortfolio::class, 'user_id', 'user_id');
    }
}

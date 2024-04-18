<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSkills extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_skills',
    ];

    public function studentValidation()
    {
        return $this->belongsTo(StudentValidation::class, 'user_id'); // Use 'user_id' as the foreign key
    }
}

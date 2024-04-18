<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'create_job_id',
        'job_tags',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'create_job_id',
        'file_path',
        'original_name',
    ];

    public function job()
    {
        return $this->belongsTo(CreateJob::class);
    }
}

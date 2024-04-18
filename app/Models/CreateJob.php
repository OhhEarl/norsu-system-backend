<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CreateJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_user_id',
        'job_title',
        'job_category_id',
        'job_description',
        'job_tags', // Add this line to allow mass assignment
        'job_start_date',
        'job_end_date',
        'job_budget_from',
        'job_budget_to',
        'job_finished',
    ];

    public function attachments()
    {
        return $this->hasMany(JobAttachment::class);
    }

    public function job_tags()
    {
        return $this->hasMany(JobTag::class);
    }
    public function job_proposals()
    {
        return $this->hasMany(JobProposal::class);
    }

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }
}

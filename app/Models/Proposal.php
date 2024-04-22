<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'freelancer_id',
        'job_title',
        'expertise_explain',
        'job_amount_bid',
        'status',
        'due_date',
    ];

    public function jobProposal()
    {
        return $this->belongsTo(CreateJob::class, 'project_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(StudentValidation::class);
    }
}

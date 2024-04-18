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
        'name',
        'description',
        'status',
        'due_date',
    ];

    public function jobProposal()
    {
        return $this->belongsTo(CreateJob::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    
    protected $table = 'feedbacks';

    protected $fillable = [
        'candidate_id', 
        'evaluator_id',
        'strengths',
        'improvements',
        'general_impression',
        'ai_generated_feedback',
        'sent_to_candidate'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
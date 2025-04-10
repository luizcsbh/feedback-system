<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['process_id', 'name', 'email', 'phone', 'resume', 'status'];

    public function process()
    {
        return $this->belongsTo(SelectionProcess::class, 'process_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
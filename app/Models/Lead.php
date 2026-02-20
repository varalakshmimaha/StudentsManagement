<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'interested_courses' => 'array',
        'next_followup_date' => 'date',
        'counselling_date' => 'date',
        'estimated_joining_date' => 'date',
        'joining_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'preferred_branch_id');
    }

    public function counsellor()
    {
        return $this->belongsTo(User::class, 'assigned_counsellor_id');
    }

    public function followups()
    {
        return $this->hasMany(LeadFollowup::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'batch_user');
    }
}

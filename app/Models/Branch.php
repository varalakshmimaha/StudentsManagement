<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    // Optional: Cast status to Enum later if needed, or simple accessors
    // Relationships can be defined here if not already (students, batches, etc.)
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}

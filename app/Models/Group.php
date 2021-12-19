<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    public function students() : HasMany {
       return $this->hasMany(Student::class);
    }

    public function subjects() : HasMany {
        return $this->hasMany(Subject::class);
    }

    public function department() : BelongsTo {
        return $this->belongsTo(Department::class);
    }
}

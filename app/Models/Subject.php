<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subject extends Model
{
    use HasFactory;

    public function group(): BelongsTo {
        return $this->belongsTo(Group::class);
    }

    public function user():  BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function sections(): HasMany {
        return $this->hasMany(Section::class);
    }
}

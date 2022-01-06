<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function section(): BelongsTo {
        return $this->belongsTo(Section::class);
    }

    public function comment(): BelongsTo {
        return $this->belongsTo(Comment::class);
    }
}

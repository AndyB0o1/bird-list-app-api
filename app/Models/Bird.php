<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bird extends Model
{
    use HasFactory;

    public function birders(): BelongsToMany {
        return $this->belongsToMany(Birder::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Birder extends Model
{
    use HasFactory;

    public function birds(): BelongsToMany {
        return $this->belongsToMany(Bird::class);
    }
}

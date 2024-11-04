<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bird extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $hidden = ['created_at', 'updated_at'];

    public function birder(): BelongsTo
    {
        return $this->belongsTo(Birder::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GoldLoss extends Model
{
    use HasFactory;

     /**
     * Get the parent imageable model (gold transform process or ...).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}

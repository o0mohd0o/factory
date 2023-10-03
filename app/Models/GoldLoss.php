<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoldLoss extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'department_id',
        'weight_in_21',
        'worker',
        'lossable_id',
        'lossable_type',
    ];

    public function getWeightIn24Attribute()  {
        return isset($this->attributes['weight_in_21'])?$this->attributes['weight_in_21']*21/24:null;
    }

     /**
     * Get the parent goldLoss model (gold transform process or ...).
     */
    public function lossable(): MorphTo
    {
        return $this->morphTo();
    }
}

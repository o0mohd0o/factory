<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoldTransformUsedItem extends Model
{
    use HasFactory;

    protected $table = 'gold_transform_used_items';

    protected $fillable = [
        'gold_transform_id',
        'item_id',
        'weight',
    ];

    /**
     * Get the item that owns the GoldTransformUsedItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
}

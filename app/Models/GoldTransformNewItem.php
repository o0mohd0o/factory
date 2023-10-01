<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoldTransformNewItem extends Model
{
    use HasFactory;

    protected $table = 'gold_transform_new_items';

    protected $fillable = [
        'item_id',
        'gold_transform_id',
        'weight',
        'actual_shares',
        'quantity',
        'stone_weight',
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

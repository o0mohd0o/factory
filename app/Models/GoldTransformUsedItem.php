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
        'department_item_id',
        'weight',
    ];

    /**
     * Get the departmentItem that owns the GoldTransformUsedItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departmentItem(): BelongsTo
    {
        return $this->belongsTo(DepartmentItem::class, 'department_item_id');
    }
}

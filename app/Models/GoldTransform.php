<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoldTransform extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'worker',
        'person_on_charge',
        'department_id',
    ];

    public function scopeDay($query, $date)
    {
        return $query->where('date', $date);
    }
    
    /**
     * Get the department that owns the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get all of the newItems for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newItems()
    {
        return $this->hasMany(GoldTransormNewItem::class, 'gold_Transform_id', 'id');
    }

    /**
     * Get all of the usedItems for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usedItems()
    {
        return $this->hasMany(GoldTransormUsedItem::class, 'gold_Transform_id', 'id');
    }
    
      /**
     * Get the post's image.
     */
    public function goldLoss(): MorphOne
    {
        return $this->morphOne(GoldLoss::class, 'gold_loss');
    }

    /**
     * Get all of the reports for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(OpeningBalanceReport::class, 'doc_num', 'id');
    }
}

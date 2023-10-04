<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class GoldTransform extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    protected $fillable = [
        'date',
        'worker_id',
        'department_id',
    ];

    public function scopeDay($query, $date)
    {
         $query->where('date', $date);
    }

    public function scopeDepartment($query, $department)
    {
         $query->where('department_id', $department);
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
     * Get the department that owns the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'id');
    }

    /**
     * Get all of the newItems for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newItems()
    {
        return $this->hasMany(GoldTransformNewItem::class, 'gold_transform_id', 'id');
    }

    /**
     * Get all of the usedItems for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usedItems()
    {
        return $this->hasMany(GoldTransformUsedItem::class, 'gold_transform_id', 'id');
    }
    
      /**
     * Get the gold transform's gold loss.
     */
    public function goldLoss(): MorphOne
    {
        return $this->morphOne(GoldLoss::class, 'lossable');
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

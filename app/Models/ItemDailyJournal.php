<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Wildside\Userstamps\Userstamps;

class ItemDailyJournal extends Model
{
    use HasFactory, Userstamps;

    protected $fillable = [
        'item_id',
        'debit',
        'credit',
        'department_id',
        'related_department_id',
        'worker_id',
        'actual_shares',
        'doc_id',
        'doc_type',
    ];

    /**
     * Get the item that owns the ItemDailyJournal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Items::class, 'item_id', 'id');
    }

    /**
     * Get the department that owns the departmentDailyJournal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get the related department that owns the departmentDailyJournal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relatedDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'related_department_id', 'id');
    }

     /**
     * Get the worker that owns the workerDailyJournal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'id');
    }

       /**
     * Get the parent doc model
     */
    public function doc(): MorphTo
    {
        return $this->morphTo();
    }

}

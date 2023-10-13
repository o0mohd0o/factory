<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Transfer extends Model
{
    use HasFactory, Userstamps;
    protected $fillable = [
        'item_id',
        'transfer_from',//from department id
        'transfer_to',//to department id
        'actual_shares',
        'weight_to_transfer',
        'person_on_charge',
        'date',
    ];

    public function scopeDay($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeDatePeriod($query, $date_from, $date_to)
    {
        return $query->whereBetween('date', [$date_from, $date_to]);
    }

    /**
     * Get the department that the Transfer is from
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromDepartment()
    {
        return $this->belongsTo(Department::class, 'transfer_from', 'id');
    }

    /**
     * Get the department that  the Transfer goes to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toDepartment()
    {
        return $this->belongsTo(Department::class, 'transfer_to', 'id');
    }
    /**
     * Get the item that  the Transfer goes to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id', 'id');
    }

     /**
     * Get the item Daily Journal
     */
    public function itemDailyJournal()
    {
        return $this->morphOne(ItemDailyJournal::class, 'doc');
    }
}

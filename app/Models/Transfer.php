<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_item_id',
        'date',
        'person_on_charge',
        'transfer_from',
        'transfer_to',
        'transfer_from_name',
        'transfer_to_name',
        'kind',
        'kind_name',
        'shares',
        'shares_to_transfer',
        'weight_to_transfer',
        'karat',
        'item_weight_before_transfer',
        'item_weight_after_transfer',
        'total_loss',
        'total_gain',
        'net_weight',
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
}

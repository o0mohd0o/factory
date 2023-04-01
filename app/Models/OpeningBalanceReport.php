<?php

namespace App\Models;

use App\Http\Traits\WeightTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningBalanceReport extends Model
{
    use HasFactory, WeightTrait;

    protected $fillable = [
        'weight',
        'transfer_to_previous_balance',
        'transfer_to_current_balance',
        'date',
        'kind',
        'kind_name',
        'karat',
        'shares',
        // 'person_on_charge', 
        'doc_num', //Opening
        'transfer_to',  //transfering to department id
        'transfer_to_name',  //transfering to department name
        'type' //create, delete, edit opening balance
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
     * Get the department that owns the OpeningBalanceReport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(department::class, 'transfer_to', 'id');
    }

    /**
     * Get the openingBalance that owns the OpeningBalanceReport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function openingBalance()
    {
        return $this->belongsTo(openingBalance::class, 'doc_num', 'id');
    }
}

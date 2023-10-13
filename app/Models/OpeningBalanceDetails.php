<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpeningBalanceDetails extends Model
{
    use HasFactory;

    protected $table = 'opening_balance_details';

    protected $fillable = [
        'item_id',
        'actual_shares',
        'unit',
        'quantity',
        'weight',
        'total_cost',
        'salary',
        'opening_balance_id',
    ];

    /**
     * Get the openingBalance that owns the OpeningBalanceDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function openingBalance()
    {
        return $this->belongsTo(openingBalance::class, 'opening_balance_id', 'id');
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

}

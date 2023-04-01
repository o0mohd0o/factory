<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HesabatTransferDetails extends Model
{
    use HasFactory;

    protected $table = 'hesabat_transfer_details';

    protected $fillable = [
        'kind',
        'kind_name',
        'karat',
        'unit',
        'quantity',
        'hesabat_transfer_id',
    ];

    /**
     * Get the openingBalance that owns the OpeningBalanceDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hesabatTransfer()
    {
        return $this->belongsTo(HesabatTransfer::class, 'hesabat_transfer_id', 'id');
    }

    
}

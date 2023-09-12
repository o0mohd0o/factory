<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeTransferDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'office_transfers_details';

    protected $fillable = [
        'kind',
        'kind_name',
        'karat',
        'shares',
        'unit',
        'quantity',
        'total_cost',
        'salary',
        'office_transfer_id ',
    ];

    /**
     * Get the openingBalance that owns the OpeningBalanceDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function officeTransfer()
    {
        return $this->belongsTo(OfficeTransfer::class, 'office_transfer_id', 'id');
    }
}

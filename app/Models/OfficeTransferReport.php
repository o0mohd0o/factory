<?php

namespace App\Models;

use App\Http\Traits\WeightTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeTransferReport extends Model
{
    use HasFactory, WeightTrait;

    protected $fillable = [
        'weight',
        'department_previous_balance',
        'department_current_balance',
        'date',
        'kind',
        'kind_name',
        'karat',
        'shares',
        'transfer_type',
        'doc_num',
        'department_id',
        'department_name',
        'type',
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
    public function officeTransfer()
    {
        return $this->belongsTo(OfficeTransfer::class, 'doc_num', 'id');
    }
}

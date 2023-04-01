<?php

namespace App\Models;

use App\Http\Traits\WeightTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class TransferReport extends Model
{
    use HasFactory, WeightTrait;

    protected $table = 'transfer_reports';

    protected $fillable = [
        'weight',
        'transfer_to_previous_balance',
        'transfer_to_current_balance',
        'transfer_from_previous_balance',
        'transfer_from_current_balance',
        'date',
        'kind',
        'kind_name', 
        'karat', 
        'shares',
        'shares_to_transfer',
        // 'doc_type', //[transfering to ,transfering from, opening balance]
        'doc_num',
        // 'statement',
        'transfer_from', //transfering from department id
        'transfer_to',  //transfering to department id
        'transfer_from_name', //transfering from department name
        'transfer_to_name',  //transfering to department name

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

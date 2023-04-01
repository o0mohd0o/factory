<?php

namespace App\Models;

use App\Http\Traits\WeightTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentDailyReport extends Model
{
    use HasFactory, WeightTrait;

    protected $fillable = [
        'previous_balance',
        'current_balance',
        'credit',
        'debit',
        'date',
        'kind',
        'kind_name',
        'shares',
        'karat',
        'department_id',
        'department_name',
    ];

   
  

    public function scopeDay($query, $date)
    {
        return $query->where('date', $date);
    }


    /**
     * Get the department that owns the DepartmentDailyReport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }


}

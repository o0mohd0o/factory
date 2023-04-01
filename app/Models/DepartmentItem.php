<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'kind',
        'kind_name',
        'karat',
        'shares',
        'current_weight',
        'previous_weight',
        'department_id',
        // 'item_id',
    ];


    public function getCurrentWeightAttribute($value)
    {
        return round($value, 3);
    }
    
    public function getPrevioustWeightAttribute($value)
    {
        return round($value, 3);
    }

    /**
     * Get the department that owns the DepartmentItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }   

}

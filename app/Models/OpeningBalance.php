<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class OpeningBalance extends Model
{
    use HasFactory, Userstamps;

    protected $fillable = [
        'date',
        'inventory_record_date',
        'person_on_charge',
        'inventory_record_num',
        'department_id',
    ];

    public function scopeDay($query, $date)
    {
        return $query->where('date', $date);
    }
    
    /**
     * Get the department that owns the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get all of the details for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(OpeningBalanceDetails::class, 'opening_balance_id', 'id');
    }

    /**
     * Get all of the reports for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(OpeningBalanceReport::class, 'doc_num', 'id');
    }
}

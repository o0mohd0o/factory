<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'person_on_charge',
        'type',
        'department_id',
    ];

    public function scopeDay($query, $date)
    {
        return $query->where('date', $date);
    }
    
    /**
     * Get the department that owns the OfficeTransfers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get all of the details for the OfficeTransfers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(OfficeTransferDetails::class, 'office_transfer_id', 'id');
    }

    /**
     * Get all of the reports for the OfficeTransfers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(OfficeTransfersReport::class, 'doc_num', 'id');
    }
}

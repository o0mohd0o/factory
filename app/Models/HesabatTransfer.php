<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HesabatTransfer extends Model
{
    use HasFactory;

    protected $table = 'hesabat_transfers';


    protected $fillable = [
        'sender_branch_id',
        'sender_branch_name',
        'date',
        'person_on_charge',
        'transfer_sanad_num',
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
        return $this->belongsTo(User::class, 'department_id', 'id');
    }

    /**
     * Get all of the details for the OpeningBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(HesabatTransferDetails::class, 'hesabat_transfer_id', 'id');
    }



}

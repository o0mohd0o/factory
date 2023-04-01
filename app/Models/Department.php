<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'main_department'
    ];

    public function getCurrentBalanceAttribute()
    {
        return $this->items()->sum('current_weight');
    }

    /**
     * Get all of the incoming transfers for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomingTransfers()
    {
        return $this->hasMany(Transfer::class, 'transfer_to', 'id');
    }

    /**
     * Get all of the outcoming transfers for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outcomingTransfers()
    {
        return $this->hasMany(Transfer::class, 'transfer_from', 'id');
    }

    

    /**
     * Get all of the items for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(DepartmentItem::class, 'department_id', 'id');
    }
    /**
     * Get all of the outcomingTransfers reports for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outcomingTransfersReports()
    {
        return $this->hasMany(TransferReport::class, 'transfer_from', 'id');
    }
    /**
     * Get all of the incomingTransfers reports for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomingTransfersReports()
    {
        return $this->hasMany(TransferReport::class, 'transfer_to', 'id');
    }

 
    /**
     * Get all of the openingBalances for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hesabatTransfers()
    {
        return $this->hasMany(HesabatTransfer::class, 'department_id', 'id');
    }

    /**
     * Get all of the openingBalances for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function openingBalances()
    {
        return $this->hasMany(OpeningBalance::class, 'department_id', 'id');
    }

    /**
     * Get all of the openingBalancesReports for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function openingBalancesReports()
    {
        return $this->hasMany(OpeningBalanceReport::class, 'transfer_to', 'id');
    }

    /**
     * Get all of the dailyReports for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dailyReports()
    {
        return $this->hasMany(DepartmentDailyReport::class, 'department_id', 'id');
    }

}

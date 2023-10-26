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
     * Get all of the incoming Office transfers for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function officeTransfers()
    {
        return $this->hasMany(OfficeTransfer::class, 'department_id', 'id');
    }

      /**
     * Get all of the incoming Office transfers for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomingOfficeTransfers()
    {
        return $this->hasMany(OfficeTransfer::class, 'department_id', 'id')->where('type', 'from');
    }

    /**
     * Get all of the outcoming Office transfers for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outcomingOfficeTransfers()
    {
        return $this->hasMany(OfficeTransfer::class, 'department_id', 'id')->where('type', 'to');
    }

}

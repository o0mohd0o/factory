<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Session;

class Worker extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_ar',
        'name_en',
        'job_name'
    ];

    public function getNameAttribute()
    {
        return Session::get('applocale', "ar") == "ar" ? $this->attributes['name_ar'] : $this->attributes['name_en'];
    }

    /**
     * Get all of the goldTranforms for the Worker
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goldTranforms(): HasMany
    {
        return $this->hasMany(GoldTransform::class, 'worker_id', 'id');
    }
}

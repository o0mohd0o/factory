<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoldLoss extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_used_gold_in_21',
        'loss_weight_in_21',
        'worker_id',
        'date',
        'lossable_id',
        'lossable_type',
    ];

    public function getLossWeightIn24Attribute()  {
        return isset($this->attributes['loss_weight_in_21'])?$this->attributes['loss_weight_in_21']*21/24:null;
    }

     /**
     * Get the parent goldLoss model (gold transform process or ...).
     */
    public function lossable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeFilterByDepartment($query, $departmentID)  {
        $query->where('department_id', $departmentID);
    }

    public function scopeFilterByWorker($query, $workerID)  {
        $query->where('worker_id', $workerID);
    }

     /**
     * Get the department that owns  the gold loss
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

     /**
     * Get the Worker that owns the gold loss
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'id');
    }

}

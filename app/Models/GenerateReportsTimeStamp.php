<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateReportsTimeStamp extends Model
{
    use HasFactory;

    protected $table = 'generated_reports_time_stamps';

    protected $fillable = [
        'date',
        'type',
    ];

    public function scopeDay($query, $date)
    {
        return $query->where('date', $date);
    }
}

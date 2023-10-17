<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintQrcode extends Model
{
    use HasFactory;
    protected $fillable = [
        'bond_num',
        'date',
        'person_on_charge',
        'count',
        'total_weight',
        'total_fare',
        'gold18',
        'gold21',
        'gold22',
        'gold24',
        'weight_all21',
        'weight_all24'
    ];

    public function details()
    {
        return $this->hasMany(PrintqrDetails::class, 'print_qrcode_id', 'id');
    }
}

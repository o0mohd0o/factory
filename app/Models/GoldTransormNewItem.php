<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldTransormNewItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'gold_transform_id',
        'actual_shares',
        'weight',
        'quantity',
        'stone_weight',
    ];
}

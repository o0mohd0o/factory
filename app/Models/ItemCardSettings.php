<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCardSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_1',
        'level_2',
        'level_3',
        'level_4',
        'level_5',
    ];
}

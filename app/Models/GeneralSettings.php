<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    use HasFactory;
    protected $table = 'general_settings';
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_description',
        'reading_data_from_hesabat',
    ];
}

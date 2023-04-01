<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintqrDetails extends Model
{
    use HasFactory;
    protected $fillable = ['item_id','serial','quantity','sales_price'];
    
    public function printQr()
    {
        return $this->belongsTo(PrintQr::class, 'print_qrcode_id', 'id');
    }

    /**
     * Get the item associated with the PrintqrDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function item()
    {
        return $this->hasOne(Items::class, 'id', 'item_id');
    }

}

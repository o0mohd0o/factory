<?php

namespace App\Models;

use App\Http\Services\ItemCardService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'sub_code',
        'fare',
        'parent_id',
        'parent_code',
        'name',
        'karat',
        'shares',
        'level_num',
        'desc_1',
        'desc_2',
        'desc_3',
        'desc_4',
        'desc_5',
    ];

    public function scopeIsNotMain($query)  {
        $query->doesntHave('childs');
    }

    /**
     * Get all of the childs for the Items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(Items::class, 'parent_id', 'id');
    }

    public function hasChilds()
    {
        if (Items::where('parent_id', $this->attributes['id'])
            ->exists()
        ) {
            return true;
        }
        return false;
    }

    public function usedBefore()
    {
        return ItemCardService::usedBefore($this);
    }
}

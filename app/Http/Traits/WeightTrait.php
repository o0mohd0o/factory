<?php

namespace App\Http\Traits;


trait WeightTrait
{
    public function unitToGram($unit = 'gram', $quantity = 1)
    {
        switch ($unit) {
            case 'kilogram':
                $convertedQuantity = $quantity * 1000;
                break;
            case 'ounce':
                $convertedQuantity = $quantity * 28.3495231;
                break;

            default:
                $convertedQuantity = $quantity;
                break;
        }
        return $convertedQuantity;
    }

    public function getWeightIn21Attribute()
    {
        if (in_array($this->attributes['karat'], ['18', '21', '22', '24'])) {
            return round(($this->attributes['weight'] * $this->attributes['karat']) / 21, 3);
        } else {
            return round(($this->attributes['weight'] * $this->attributes['karat']) / 875, 3);
        }
    }
    public function getWeightIn24Attribute()
    {
        if (in_array($this->attributes['karat'], ['18', '21', '22', '24'])) {
            return round(($this->attributes['weight'] * $this->attributes['karat']) / 24, 3);
        } else {
            return round(($this->attributes['weight'] * $this->attributes['karat']) / 1000, 3);
        }
    }
}

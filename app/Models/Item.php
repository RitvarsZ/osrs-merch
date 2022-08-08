<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'name',
        'examine',
        'icon',
        'members',
        'lowalch',
        'highalch',
        'limit',
    ];

    /**
     * Item prices
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(ItemPrice::class);
    }

    /**
     * Latest price for this item.
     *
     * @return ItemPrice | null
     */
    public function latestPrice()
    {
        return $this->hasOne(ItemPrice::class)->latest();
    }
}

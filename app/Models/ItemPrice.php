<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    use HasFactory;

    protected $dates = ['high_time', 'low_time', 'created_at', 'updated_at'];

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'item_id',
        'high',
        'high_time',
        'low',
        'low_time',
    ];

    /**
     * Item this price is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

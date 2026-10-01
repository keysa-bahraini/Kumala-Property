<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'date',
        'property_id',
        'property_title',
        'property_image',
        'location',
        'trx_type',
        'asking_price',
        'deal_price',
        'deal_val',
        'diff_pct',
        'parties',
        'commission_val',
        'notary',
        'status_doc',
        'days_on_market',
    ];

    protected $casts = [
        'deal_val' => 'integer',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}

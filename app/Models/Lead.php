<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'property_id',
        'property_title',
        'property_price',
        'client_name',
        'client_phone',
        'client_email',
        'buyer_category',
        'message',
        'quote',
        'inquiry_type',
        'payment_scheme',
        'survey_date_preference',
        'assigned_agent',
        'stage',
        'sla_minutes',
        'sla_badge',
        'sla_status',
        'time_ago',
    ];

    protected $casts = [
        'sla_minutes' => 'integer',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class, 'lead_id', 'id');
    }
}

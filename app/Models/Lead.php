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
        'buyer_type',
        'message',
        'quote',
        'inquiry_type',
        'payment_scheme',
        'survey_date_preference',
        'assigned_agent',
        'assigned_to',
        'stage',
        'sla_minutes',
        'sla_badge',
        'sla_status',
        'time_ago',
    ];

    protected $casts = [
        'sla_minutes' => 'integer',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->client_name;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->client_phone;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->client_email;
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class, 'lead_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'property_id',
        'property_slug',
        'title',
        'owner_name',
        'owner_phone',
        'owner_email',
        'owner_status',
        'trx_type',
        'category_id',
        'region_id',
        'category',
        'city_region',
        'specific_area',
        'location',
        'address',
        'asking_price',
        'asking_val',
        'valuation_range',
        'land_size',
        'building_size',
        'bedrooms',
        'bathrooms',
        'electricity',
        'legal',
        'facilities',
        'photos',
        'photos_count',
        'priority',
        'status',
        'checklist',
        'notes',
        'time_ago',
        'submitted_at',
    ];

    protected $casts = [
        'asking_val' => 'integer',
        'land_size' => 'integer',
        'building_size' => 'integer',
        'photos_count' => 'integer',
        'facilities' => 'array',
        'photos' => 'array',
        'checklist' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function getOwnerAttribute(): ?string
    {
        return $this->owner_name;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->owner_phone;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->owner_email;
    }

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function regionRelation(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function checklist(): HasOne
    {
        return $this->hasOne(SubmissionChecklist::class, 'submission_id', 'id');
    }

    public function checklists(): HasOne
    {
        return $this->hasOne(SubmissionChecklist::class, 'submission_id', 'id');
    }
}

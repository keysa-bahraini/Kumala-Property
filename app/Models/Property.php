<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'slug',
        'title',
        'category_id',
        'region_id',
        'category',
        'transaction_type',
        'price',
        'price_formatted',
        'price_short',
        'price_label',
        'price_notes',
        'price_negotiable',
        'location_city',
        'location_area',
        'location_address',
        'legal_status',
        'verified',
        'featured',
        'highlight_badge',
        'land_size',
        'building_size',
        'floors',
        'bedrooms',
        'bathrooms',
        'orientation',
        'electricity',
        'water_source',
        'garage',
        'year_built',
        'furnishing',
        'specs_tags',
        'image_main',
        'images',
        'features',
        'video_tour_url',
        'pdf_factsheet_url',
        'description',
        'distance_points',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
        'price_negotiable' => 'boolean',
        'verified' => 'boolean',
        'featured' => 'boolean',
        'land_size' => 'integer',
        'building_size' => 'integer',
        'floors' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'specs_tags' => 'array',
        'images' => 'array',
        'features' => 'array',
        'distance_points' => 'array',
    ];

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function regionRelation(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PropertyPhoto::class, 'property_id', 'id')->orderBy('sort_order');
    }

    public function features(): HasMany
    {
        return $this->hasMany(PropertyFeature::class, 'property_id', 'id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'property_id', 'id');
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class, 'property_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'property_id', 'id');
    }
}

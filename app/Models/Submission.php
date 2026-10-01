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
        'title',
        'owner_name',
        'owner_phone',
        'owner_email',
        'owner_status',
        'trx_type',
        'category_id',
        'region_id',
        'category',
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
        'priority',
        'photos_count',
        'status',
        'notes',
        'submitted_at',
    ];

    protected $casts = [
        'asking_val' => 'integer',
        'land_size' => 'integer',
        'building_size' => 'integer',
        'photos_count' => 'integer',
        'submitted_at' => 'datetime',
    ];

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

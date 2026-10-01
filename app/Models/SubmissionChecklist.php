<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'identity',
        'maps',
        'shm_match',
        'photos_hd',
    ];

    protected $casts = [
        'identity' => 'boolean',
        'maps' => 'boolean',
        'shm_match' => 'boolean',
        'photos_hd' => 'boolean',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id', 'id');
    }
}

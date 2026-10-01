<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'timestamp_display',
        'action',
        'user',
        'status',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];
}

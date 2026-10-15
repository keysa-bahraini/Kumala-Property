<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'type',
        'title',
        'owner',
        'phone',
        'time_ago',
        'sla_status',
        'sla_badge',
        'detail',
        'btn_action_1',
        'btn_action_2',
        'is_resolved',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];
}

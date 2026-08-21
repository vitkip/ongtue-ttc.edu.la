<?php

namespace App\Models;

use App\Models\Concerns\HasResolvableImageUrl;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasResolvableImageUrl;

    protected $fillable = [
        'category',
        'title',
        'description',
        'image_url',
        'badge_label',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'registration_url',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}

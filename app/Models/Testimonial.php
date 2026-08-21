<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $fillable = [
        'full_name',
        'role',
        'quote',
        'photo_url',
        'sort_order',
        'is_active',
    ];

    protected $appends = [
        'resolved_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getResolvedPhotoUrlAttribute(): ?string
    {
        if (! $this->photo_url) {
            return null;
        }

        return str_starts_with($this->photo_url, 'http')
            ? $this->photo_url
            : Storage::disk('public')->url($this->photo_url);
    }
}

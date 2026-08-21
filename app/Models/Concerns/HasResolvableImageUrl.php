<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasResolvableImageUrl
{
    public function getResolvedImageUrlAttribute(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        return str_starts_with($this->image_url, 'http')
            ? $this->image_url
            : Storage::disk('public')->url($this->image_url);
    }
}

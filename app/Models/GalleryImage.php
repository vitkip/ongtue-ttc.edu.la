<?php

namespace App\Models;

use App\Models\Concerns\HasResolvableImageUrl;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasResolvableImageUrl;

    protected $fillable = [
        'image_url',
        'alt_text',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}

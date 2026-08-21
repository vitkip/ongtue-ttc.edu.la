<?php

namespace App\Models;

use App\Models\Concerns\HasResolvableImageUrl;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    use HasResolvableImageUrl;

    protected $fillable = [
        'image_url',
        'badge_text',
        'title_line1',
        'title_line2',
        'description',
        'primary_button_text',
        'primary_button_link',
        'secondary_button_text',
        'secondary_button_link',
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

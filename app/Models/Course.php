<?php

namespace App\Models;

use App\Models\Concerns\HasResolvableImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasResolvableImageUrl;

    protected $fillable = [
        'category_id',
        'image_url',
        'badge_label',
        'title',
        'description',
        'fee_type',
        'icon',
        'fee_label',
        'duration_label',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'major_id');
    }
}

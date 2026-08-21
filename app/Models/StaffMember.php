<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class StaffMember extends Model
{
    protected $fillable = [
        'department_id',
        'full_name',
        'title',
        'photo_url',
        'bio',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(OrgDepartment::class, 'department_id');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrgDepartment extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'role_label',
        'icon',
        'sort_order',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrgDepartment::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(OrgDepartment::class, 'parent_id')->orderBy('sort_order');
    }

    public function staffMembers(): HasMany
    {
        return $this->hasMany(StaffMember::class, 'department_id');
    }
}

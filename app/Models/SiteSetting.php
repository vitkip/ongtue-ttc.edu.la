<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'group_name',
        'label',
        'sort_order',
    ];

    public static function group(string $group): array
    {
        return static::where('group_name', $group)
            ->orderBy('sort_order')
            ->pluck('setting_value', 'setting_key')
            ->all();
    }
}

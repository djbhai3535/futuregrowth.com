<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function getVal($key, $default = null)
    {
        $setting = \Illuminate\Support\Facades\Cache::rememberForever("setting_{$key}", function () use ($key) {
            return self::where('key', $key)->first();
        });
        
        if (!$setting) return $default;

        return match ($setting->type) {
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            'boolean' => (bool) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    public static function get(string $group, string $key, mixed $default = null): mixed
    {
        $record = self::where('group', $group)->where('key', $key)->first();
        if (! $record) {
            return $default;
        }
        return match ($record->type) {
            'boolean' => (bool) $record->value,
            'integer' => (int) $record->value,
            'array' => json_decode($record->value, true),
            default => $record->value,
        };
    }

    public static function set(string $group, string $key, mixed $value, string $type = 'string'): void
    {
        $encoded = match ($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) $value,
            'array' => json_encode($value),
            default => (string) $value,
        };

        self::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $encoded, 'type' => $type]
        );
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saved(function (Setting $setting) {
            \Illuminate\Support\Facades\Cache::forget("setting:{$setting->group}:{$setting->key}");
        });
    }

    public static function cached(string $group, string $key, mixed $default = null): mixed
    {
        return cache()->remember("setting:{$group}:{$key}", 3600, fn () => self::get($group, $key, $default));
    }

    public static function clearCache(): void
    {
        self::all()->each(function (Setting $s) {
            \Illuminate\Support\Facades\Cache::forget("setting:{$s->group}:{$s->key}");
        });
    }
}

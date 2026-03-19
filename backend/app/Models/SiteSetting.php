<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("site_setting.{$key}", 60, function () use ($key, $default) {
            try {
                return static::where('key', $key)->value('value') ?? $default;
            } catch (\Throwable) {
                return $default;
            }
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("site_setting.{$key}");
    }

    public static function isMaintenanceMode(): bool
    {
        return (bool) static::get('maintenance_mode', false);
    }

    public static function getMaintenanceMessage(): ?array
    {
        $msg = static::get('maintenance_message');

        return $msg ? json_decode($msg, true) : null;
    }

    public static function getJson(string $key, mixed $default = null): mixed
    {
        $value = static::get($key);

        return $value ? json_decode($value, true) ?? $default : $default;
    }
}

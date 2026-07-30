<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class SiteSetting extends Model
{
    use LogsActivity;

    /**
     * Fallback recipient for Carsurf reservation requests.
     *
     * Used when `carsurf_reservas_recipients` is unset or holds no valid
     * address, so a reservation is never sent nowhere.
     */
    public const CARSURF_RESERVAS_FALLBACK = 'geral@carsurf.nazare.pt';

    protected $fillable = ['key', 'value'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Definição criada',
                'updated' => 'Definição atualizada',
                'deleted' => 'Definição eliminada',
                default => "Definição {$eventName}",
            });
    }

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

    /**
     * Recipients for Carsurf reservation requests.
     *
     * Parses the comma-separated `carsurf_reservas_recipients` setting and
     * keeps only valid addresses. Falls back to CARSURF_RESERVAS_FALLBACK when
     * nothing valid remains.
     *
     * @return array<int, string>
     */
    public static function carsurfReservasRecipients(): array
    {
        $configured = (string) static::get('carsurf_reservas_recipients', '');

        $recipients = collect(explode(',', $configured))
            ->map(fn (string $email) => trim($email))
            ->filter(fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->unique()
            ->values()
            ->all();

        return $recipients ?: [self::CARSURF_RESERVAS_FALLBACK];
    }

    public static function getJson(string $key, mixed $default = null): mixed
    {
        $value = static::get($key);

        return $value ? json_decode($value, true) ?? $default : $default;
    }
}

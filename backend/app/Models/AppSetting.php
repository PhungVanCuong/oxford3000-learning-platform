<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getJson(string $key, array $default = []): array
    {
        try {
            $record = static::where('key', $key)->first();
            if (!$record || !$record->value) {
                return $default;
            }
            $decoded = json_decode($record->value, true);
            return is_array($decoded) ? $decoded : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function setJson(string $key, array $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => json_encode($value, JSON_UNESCAPED_UNICODE)]
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function get(string $key, $default = null)
    {
        $settingData = \Illuminate\Support\Facades\Cache::rememberForever("setting_{$key}", function () use ($key) {
            $record = self::where('key', $key)->first();
            return $record ? $record->toArray() : null;
        });

        if (!$settingData) return $default;

        return match ($settingData['type']) {
            'boolean' => filter_var($settingData['value'], FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $settingData['value'],
            'float' => (float) $settingData['value'],
            'json' => json_decode($settingData['value'], true),
            default => $settingData['value'],
        };
    }

    public static function set(string $key, $value, ?string $type = null, string $group = 'general')
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        } elseif (is_null($type)) {
            // Auto detect type if not explicitly provided
            if (is_bool($value) || $value === 'true' || $value === 'false') {
                $type = 'boolean';
                $value = is_bool($value) ? ($value ? 'true' : 'false') : $value;
            } elseif (is_int($value) || (is_string($value) && ctype_digit($value))) {
                $type = 'integer';
            } elseif (is_float($value) || (is_numeric($value) && strpos($value, '.') !== false)) {
                $type = 'float';
            } else {
                $type = 'string';
            }
        }

        $record = self::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type, 'group' => $group]
        );

        \Illuminate\Support\Facades\Cache::forget("setting_{$key}");

        return $record;
    }
}

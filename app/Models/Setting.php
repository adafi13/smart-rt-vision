<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function set(string $key, $value, string $type = 'string', string $group = 'general')
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type, 'group' => $group]
        );
    }
}

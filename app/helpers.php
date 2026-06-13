<?php

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            $setting = \Illuminate\Support\Facades\Cache::remember("setting_{$key}", 3600, function () use ($key) {
                return \Illuminate\Support\Facades\DB::table('settings')->where('key', $key)->first();
            });
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EzvizService
{
    /**
     * Get the cached EZVIZ access token for a tenant, or fetch a new one if expired.
     */
    public static function getAccessToken($tenantId)
    {
        $appKey = Setting::get("tenant_{$tenantId}_ezviz_app_key");
        $appSecret = Setting::get("tenant_{$tenantId}_ezviz_app_secret");

        if (!$appKey || !$appSecret) {
            return null; // Not configured
        }

        // Cache the token for 6 days (EZVIZ tokens typically expire in 7 days)
        return Cache::remember("ezviz_token_tenant_{$tenantId}", now()->addDays(6), function () use ($appKey, $appSecret) {
            try {
                $response = Http::asForm()->post('https://open.ezvizlife.com/api/lapp/token/get', [
                    'appKey' => $appKey,
                    'appSecret' => $appSecret,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['code']) && $data['code'] == '200' && isset($data['data']['accessToken'])) {
                        return $data['data']['accessToken'];
                    }
                    
                    Log::error('EZVIZ API Error: ' . json_encode($data));
                }
            } catch (\Exception $e) {
                Log::error('EZVIZ API Exception: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Force clear the cached token for a tenant (e.g. when credentials are updated).
     */
    public static function clearCachedToken($tenantId)
    {
        Cache::forget("ezviz_token_tenant_{$tenantId}");
    }
}

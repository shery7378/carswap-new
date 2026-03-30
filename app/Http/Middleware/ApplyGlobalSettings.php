<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class ApplyGlobalSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cache settings to prevent DB hits on every single request
        $settings = Cache::remember('global_settings', 3600, function () {
            return Setting::whereIn('key', ['language', 'timezone'])->pluck('value', 'key')->toArray();
        });

        // Set Language (Locale)
        if (isset($settings['language'])) {
            App::setLocale($settings['language']);
        } else {
            // Default to Hungarian since it's the requested default
            App::setLocale('hu');
        }

        // Set Timezone
        if (isset($settings['timezone'])) {
            date_default_timezone_set($settings['timezone']);
            Config::set('app.timezone', $settings['timezone']);
        } else {
            date_default_timezone_set('Europe/Budapest');
            Config::set('app.timezone', 'Europe/Budapest');
        }

        return $next($request);
    }
}

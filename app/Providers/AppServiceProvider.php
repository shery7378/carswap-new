<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // Grant super-admin all permissions
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin', 'admin-guard') ? true : null;
        });

        // Load dynamic settings from cache or database (Caching prevents 'max_connections_per_hour' errors)
        try {
            $allSettings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    return \App\Models\Setting::pluck('value', 'key');
                }
                return collect([]);
            });

            if ($allSettings->isNotEmpty()) {
                // 1. Language (Locale)
                if ($allSettings->has('language')) {
                    app()->setLocale($allSettings->get('language'));
                } else {
                    app()->setLocale('hu'); // Default
                }

                // 2. Timezone
                if ($allSettings->has('timezone')) {
                    date_default_timezone_set($allSettings->get('timezone'));
                    config(['app.timezone' => $allSettings->get('timezone')]);
                } else {
                    date_default_timezone_set('Europe/Budapest');
                    config(['app.timezone' => 'Europe/Budapest']);
                }

                // 3. Mail configuration
                if ($allSettings->has('mail_host')) {
                    config([
                        'mail.default' => $allSettings->get('mail_mailer', 'smtp'),
                        'mail.mailers.smtp.host' => $allSettings->get('mail_host'),
                        'mail.mailers.smtp.port' => $allSettings->get('mail_port'),
                        'mail.mailers.smtp.encryption' => ($allSettings->get('mail_encryption') === 'none' || !$allSettings->get('mail_encryption')) ? null : $allSettings->get('mail_encryption'),
                        'mail.mailers.smtp.username' => $allSettings->get('mail_username'),
                        'mail.mailers.smtp.password' => $allSettings->get('mail_password'),
                        'mail.from.address' => $allSettings->get('mail_from_address'),
                        'mail.from.name' => $allSettings->get('mail_from_name'),
                    ]);
                }

                // 4. Make all settings available via config('settings.xxx')
                config(['settings' => $allSettings->toArray()]);
            }
        } catch (\Exception $e) {
            // Silently fail if DB connection is not ready or cache fails
        }
    }
}

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

        // Load dynamic settings from database
        if (Schema::hasTable('settings')) {
            try {
                $allSettings = \App\Models\Setting::pluck('value', 'key');
                
                // Set mail configuration
                if ($allSettings->has('mail_host')) {
                    config([
                        'mail.mailers.smtp.host' => $allSettings->get('mail_host'),
                        'mail.mailers.smtp.port' => $allSettings->get('mail_port'),
                        'mail.mailers.smtp.encryption' => $allSettings->get('mail_encryption'),
                        'mail.mailers.smtp.username' => $allSettings->get('mail_username'),
                        'mail.mailers.smtp.password' => $allSettings->get('mail_password'),
                        'mail.from.address' => $allSettings->get('mail_from_address'),
                        'mail.from.name' => $allSettings->get('mail_from_name'),
                    ]);
                }

                // Make all settings available via config('settings.xxx')
                config(['settings' => $allSettings->toArray()]);
                
            } catch (\Exception $e) {
                // Silently fail if DB connection is not ready
            }
        }
    }
}

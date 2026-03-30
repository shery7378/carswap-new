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

        // Load dynamic mail configuration from database
        if (Schema::hasTable('settings')) {
            try {
                $mailSettings = \App\Models\Setting::where('key', 'like', 'mail_%')
                    ->orWhere('key', 'enable_email_notifications')
                    ->pluck('value', 'key');

                if ($mailSettings->has('mail_host')) {
                    config([
                        'mail.mailers.smtp.host' => $mailSettings->get('mail_host'),
                        'mail.mailers.smtp.port' => $mailSettings->get('mail_port'),
                        'mail.mailers.smtp.encryption' => $mailSettings->get('mail_encryption'),
                        'mail.mailers.smtp.username' => $mailSettings->get('mail_username'),
                        'mail.mailers.smtp.password' => $mailSettings->get('mail_password'),
                        'mail.from.address' => $mailSettings->get('mail_from_address'),
                        'mail.from.name' => $mailSettings->get('mail_from_name'),
                    ]);
                }
            } catch (\Exception $e) {
                // Silently fail if DB connection is not ready
            }
        }
    }
}

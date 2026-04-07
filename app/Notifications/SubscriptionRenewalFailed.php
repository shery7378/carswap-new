<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Subscription;

class SubscriptionRenewalFailed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, $reason)
    {
        $this->subscription = $subscription;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Important: Subscription Renewal Failed (Insufficient Balance)')
            ->greeting("Hello {$notifiable->first_name},")
            ->line('We were unable to process your subscription renewal payment.')
            ->line("Reason: Insufficient Balance or Payment Method Issue.")
            ->line('As a result, your subscription has been expired, and your account has been moved to our **FREE** package.')
            ->action('Update Payment Method', url(config('app.frontend_url') . '/account/billing'))
            ->line('Please update your payment method to restore your previous package benefits.')
            ->line('Thank you for your understanding!');
    }
}

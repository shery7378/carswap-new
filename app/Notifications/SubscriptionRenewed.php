<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Subscription;

class SubscriptionRenewed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;
    protected $amount;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, $amount)
    {
        $this->subscription = $subscription;
        $this->amount = $amount;
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
        $planName = $this->subscription->plan?->name ?? 'Package';
        
        return (new MailMessage)
            ->subject('Your subscription has been renewed!')
            ->greeting("Hello {$notifiable->first_name},")
            ->line("Good news! Your {$planName} subscription has been automatically renewed.")
            ->line("Amount Charged: " . number_format($this->amount, 0, '.', ',') . " HUF")
            ->line("Next Renewal Date: " . $this->subscription->next_billing_at->format('Y-m-d'))
            ->line('Thank you for being a part of CarSwap!')
            ->action('View Subscription', url(config('app.frontend_url') . '/account/billing'))
            ->line('Wishing you a great day!');
    }
}

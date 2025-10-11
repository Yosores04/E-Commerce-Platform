<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Xerxia Marketplace!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to Xerxia Marketplace! We are excited to have you on board.')
            ->line('Your account has been successfully created.')
            ->line('Start exploring thousands of products from our trusted vendors.')
            ->action('Start Shopping', url('/products'))
            ->line('If you have any questions, our support team is here to help.')
            ->line('Happy shopping!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Welcome to Xerxia Marketplace! Your account has been created successfully.',
        ];
    }
}

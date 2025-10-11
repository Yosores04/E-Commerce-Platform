<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $vendor;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
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
            ->subject('Congratulations! Your Vendor Account Has Been Approved')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your vendor account has been approved.')
            ->line('Shop Name: ' . $this->vendor->shop_name)
            ->line('Business Name: ' . $this->vendor->business_name)
            ->line('You can now start adding products to your shop and begin selling on our marketplace.')
            ->action('Go to Vendor Dashboard', url('/vendor/dashboard'))
            ->line('If you have any questions, feel free to contact our support team.')
            ->line('Thank you for joining Xerxia Marketplace!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'vendor_id' => $this->vendor->id,
            'shop_name' => $this->vendor->shop_name,
            'message' => 'Your vendor account "' . $this->vendor->shop_name . '" has been approved!',
        ];
    }
}

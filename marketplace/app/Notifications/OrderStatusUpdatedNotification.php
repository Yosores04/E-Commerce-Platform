<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
        $message = (new MailMessage)
            ->subject('Order Status Updated - #' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your order status has been updated.')
            ->line('Order Number: ' . $this->order->order_number)
            ->line('Previous Status: ' . ucfirst($this->oldStatus))
            ->line('New Status: ' . ucfirst($this->newStatus));

        // Add specific messages based on status
        switch ($this->newStatus) {
            case 'processing':
                $message->line('Your order is now being processed.');
                break;
            case 'shipped':
                $message->line('Great news! Your order has been shipped.');
                if ($this->order->tracking_number) {
                    $message->line('Tracking Number: ' . $this->order->tracking_number);
                }
                break;
            case 'delivered':
                $message->line('Your order has been delivered successfully!');
                $message->line('We hope you enjoy your purchase.');
                break;
            case 'cancelled':
                $message->line('Your order has been cancelled.');
                $message->line('If you have any questions, please contact our support team.');
                break;
        }

        $message->action('View Order', url('/orders/' . $this->order->id))
            ->line('Thank you for shopping with us!');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Order #' . $this->order->order_number . ' status changed to ' . ucfirst($this->newStatus),
        ];
    }
}

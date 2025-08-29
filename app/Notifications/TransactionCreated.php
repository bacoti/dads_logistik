<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionCreated extends Notification
{
    use Queueable;

    protected $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Transaksi baru telah dibuat.')
            ->line('Tipe: ' . ucfirst($this->transaction->type))
            ->line('Lokasi: ' . $this->transaction->location)
            ->action('Lihat Detail', url('/admin/transactions/' . $this->transaction->id))
            ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Transaksi Baru',
            'message' => 'Transaksi ' . ucfirst($this->transaction->type) . ' baru telah dibuat oleh ' . $this->transaction->user->name,
            'transaction_id' => $this->transaction->id,
            'transaction_type' => $this->transaction->type,
            'location' => $this->transaction->location,
            'user_name' => $this->transaction->user->name,
            'created_at' => $this->transaction->created_at->format('d M Y H:i'),
            'action_url' => '/admin/transactions/' . $this->transaction->id,
            'icon' => 'fas fa-plus-circle',
            'type' => 'success'
        ];
    }
}

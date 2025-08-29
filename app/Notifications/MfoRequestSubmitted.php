<?php

namespace App\Notifications;

use App\Models\MfoRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MfoRequestSubmitted extends Notification
{
    use Queueable;

    protected $mfoRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(MfoRequest $mfoRequest)
    {
        $this->mfoRequest = $mfoRequest;
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
            ->line('MFO Request baru telah diajukan.')
            ->line('Material: ' . $this->mfoRequest->material_name)
            ->line('Quantity: ' . $this->mfoRequest->quantity . ' ' . $this->mfoRequest->unit)
            ->action('Lihat Detail', url('/admin/mfo-requests/' . $this->mfoRequest->id))
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
            'title' => 'MFO Request Baru',
            'message' => 'MFO Request untuk ' . $this->mfoRequest->material_name . ' telah diajukan oleh ' . $this->mfoRequest->user->name,
            'mfo_request_id' => $this->mfoRequest->id,
            'material_name' => $this->mfoRequest->material_name,
            'quantity' => $this->mfoRequest->quantity,
            'unit' => $this->mfoRequest->unit,
            'user_name' => $this->mfoRequest->user->name,
            'created_at' => $this->mfoRequest->created_at->format('d M Y H:i'),
            'action_url' => '/admin/mfo-requests/' . $this->mfoRequest->id,
            'icon' => 'fas fa-file-alt',
            'type' => 'info'
        ];
    }
}

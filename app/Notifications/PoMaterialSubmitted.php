<?php

namespace App\Notifications;

use App\Models\PoMaterial;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PoMaterialSubmitted extends Notification
{
    use Queueable;

    protected $poMaterial;

    /**
     * Create a new notification instance.
     */
    public function __construct(PoMaterial $poMaterial)
    {
        $this->poMaterial = $poMaterial;
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
            ->line('PO Material baru telah diajukan.')
            ->line('PO Number: ' . $this->poMaterial->po_number)
            ->line('Supplier: ' . $this->poMaterial->supplier)
            ->action('Lihat Detail', url('/admin/po-materials/' . $this->poMaterial->id))
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
            'title' => 'PO Material Baru',
            'message' => 'PO Material ' . $this->poMaterial->po_number . ' telah diajukan oleh ' . $this->poMaterial->user->name,
            'po_material_id' => $this->poMaterial->id,
            'po_number' => $this->poMaterial->po_number,
            'supplier' => $this->poMaterial->supplier,
            'quantity' => $this->poMaterial->quantity,
            'unit' => $this->poMaterial->unit,
            'user_name' => $this->poMaterial->user->name,
            'created_at' => $this->poMaterial->created_at->format('d M Y H:i'),
            'action_url' => '/admin/po-materials/' . $this->poMaterial->id,
            'icon' => 'fas fa-box',
            'type' => 'info'
        ];
    }
}

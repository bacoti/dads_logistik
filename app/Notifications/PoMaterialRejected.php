<?php

namespace App\Notifications;

use App\Models\PoMaterial;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PoMaterialRejected extends Notification
{
    use Queueable;

    protected $poMaterial;
    protected $rejectionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(PoMaterial $poMaterial, string $rejectionReason = null)
    {
        $this->poMaterial = $poMaterial;
        $this->rejectionReason = $rejectionReason;
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
        $mail = (new MailMessage)
            ->line('PO Material Anda telah ditolak.')
            ->line('PO Number: ' . $this->poMaterial->po_number);

        if ($this->rejectionReason) {
            $mail->line('Alasan: ' . $this->rejectionReason);
        }

        return $mail->action('Lihat Detail', url('/po/materials/' . $this->poMaterial->id))
            ->line('Silakan perbaiki dan ajukan kembali.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'PO Material Ditolak',
            'message' => 'PO Material ' . $this->poMaterial->po_number . ' telah ditolak oleh admin',
            'po_material_id' => $this->poMaterial->id,
            'po_number' => $this->poMaterial->po_number,
            'rejection_reason' => $this->rejectionReason,
            'rejected_at' => now()->format('d M Y H:i'),
            'action_url' => '/po/materials/' . $this->poMaterial->id,
            'icon' => 'fas fa-times-circle',
            'type' => 'error'
        ];
    }
}

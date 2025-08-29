<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\PoMaterial;
use App\Models\MfoRequest;
use App\Notifications\TransactionCreated;
use App\Notifications\PoMaterialApproved;
use App\Notifications\PoMaterialRejected;
use App\Notifications\PoMaterialSubmitted;
use App\Notifications\MfoRequestSubmitted;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send notification when a new transaction is created
     */
    public function notifyTransactionCreated(Transaction $transaction)
    {
        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new TransactionCreated($transaction));
    }

    /**
     * Send notification when PO Material is submitted
     */
    public function notifyPoMaterialSubmitted(PoMaterial $poMaterial)
    {
        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new PoMaterialSubmitted($poMaterial));
    }

    /**
     * Send notification when PO Material is approved
     */
    public function notifyPoMaterialApproved(PoMaterial $poMaterial)
    {
        // Kirim notifikasi ke user yang mengajukan PO
        $poMaterial->user->notify(new PoMaterialApproved($poMaterial));
    }

    /**
     * Send notification when PO Material is rejected
     */
    public function notifyPoMaterialRejected(PoMaterial $poMaterial, string $rejectionReason = null)
    {
        // Kirim notifikasi ke user yang mengajukan PO
        $poMaterial->user->notify(new PoMaterialRejected($poMaterial, $rejectionReason));
    }

    /**
     * Send notification when MFO Request is submitted
     */
    public function notifyMfoRequestSubmitted(MfoRequest $mfoRequest)
    {
        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new MfoRequestSubmitted($mfoRequest));
    }

    /**
     * Send notification to specific user
     */
    public function sendToUser(User $user, $notification)
    {
        $user->notify($notification);
    }

    /**
     * Send notification to users by role
     */
    public function sendToRole(string $role, $notification)
    {
        $users = User::where('role', $role)->get();
        Notification::send($users, $notification);
    }

    /**
     * Send notification to all users
     */
    public function sendToAllUsers($notification)
    {
        $users = User::all();
        Notification::send($users, $notification);
    }

    /**
     * Get notification statistics for dashboard
     */
    public function getNotificationStats()
    {
        return [
            'total_users' => User::count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'field_user_count' => User::where('role', 'user')->count(),
            'po_user_count' => User::where('role', 'po')->count(),
        ];
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Bagikan data notifikasi ke view 'layouts.navigation'
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $notifications = Notification::where('user_id', Auth::id())
                                            ->where('is_read', false)
                                            ->latest()
                                            ->take(5)
                                            ->get();
                $unreadCount = Notification::where('user_id', Auth::id())
                                            ->where('is_read', false)
                                            ->count();

                $view->with('notifications', $notifications)->with('unreadCount', $unreadCount);
            }
        });
    }
}

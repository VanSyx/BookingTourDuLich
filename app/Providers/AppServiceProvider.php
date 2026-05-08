<?php

namespace App\Providers;

use App\Models\admin\ContactModel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('admin.blocks.sidebar', function ($view) {
            $contactModel = new ContactModel();
            $unreadData   = $contactModel->countContactsUnread();

            // Thông báo bổ sung
            $newBookingsCount = $contactModel->countNewBookings();
            $cancelledBookingsCount = $contactModel->countCancelledBookings(); // NEW
            $newUsersCount    = $contactModel->countNewUsers();
            $newReviewsCount  = $contactModel->countNewReviews();
            $newBookingsList  = $contactModel->getNewBookings();
            $cancelledBookingsList = $contactModel->getCancelledBookings(); // NEW

            // Tổng badge = liên hệ chưa reply + booking mới + booking hủy + user mới + review mới
            $totalNotifications = $unreadData['countUnread']
                                + $newBookingsCount
                                + $cancelledBookingsCount
                                + $newUsersCount
                                + $newReviewsCount;

            $view->with('unreadCount',        $unreadData['countUnread']);
            $view->with('unreadContacts',     $unreadData['contacts']);
            $view->with('newBookingsCount',   $newBookingsCount);
            $view->with('newBookingsList',    $newBookingsList);
            $view->with('cancelledBookingsCount', $cancelledBookingsCount); // NEW
            $view->with('cancelledBookingsList', $cancelledBookingsList); // NEW
            $view->with('newUsersCount',      $newUsersCount);
            $view->with('newReviewsCount',    $newReviewsCount);
            $view->with('totalNotifications', $totalNotifications);
        });
    }
}

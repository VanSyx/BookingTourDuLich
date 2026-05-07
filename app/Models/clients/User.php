<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use HasFactory;

    protected $table = 'tbl_users';


    public function getUserId($username)
    {
        return DB::table($this->table)
            ->select('userId')
            ->where('username', $username)->value('userId');
    }
    public function getUser($id)
    {
        $users = DB::table($this->table)
            ->where('userId', $id)->first();

        return $users;
    }

    public function updateUser($id, $data)
    {
        $update = DB::table($this->table)
            ->where('userid', $id)
            ->update($data);

        return $update;
    }

    public function getMyTours($id)
    {
        $myTours = DB::table('tbl_booking')
            ->join('tbl_tours',    'tbl_booking.tourId',    '=', 'tbl_tours.tourId')
            ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
            ->where('tbl_booking.userId', $id)
            ->select(
                'tbl_booking.bookingId',
                'tbl_booking.tourId',
                'tbl_booking.fullName',
                'tbl_booking.email',
                'tbl_booking.phoneNumber',
                'tbl_booking.numAdults',
                'tbl_booking.numChildren',
                'tbl_booking.totalPrice',
                'tbl_booking.bookingStatus',
                'tbl_booking.bookingDate',
                'tbl_checkout.checkoutId',
                'tbl_checkout.paymentStatus',
                'tbl_checkout.paymentMethod',
                'tbl_tours.title',
                'tbl_tours.description',
                'tbl_tours.destination',
                'tbl_tours.time',
                'tbl_tours.startDate',
                'tbl_tours.endDate',
                'tbl_tours.priceAdult',
                'tbl_tours.priceChild'
            )
            ->orderByDesc('tbl_booking.bookingDate')
            ->get();

        foreach ($myTours as $tour) {
            $tour->rating = DB::table('tbl_reviews')
                ->where('tourId', $tour->tourId)
                ->where('userId', $id)
                ->value('rating');
            $tour->images = DB::table('tbl_images')
                ->where('tourId', $tour->tourId)
                ->pluck('imageUrl');
        }

        return $myTours;
    }
}

<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContactModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_contact';

    public function getContacts()
    {
        return DB::table($this->table)
            ->where('isReply', 'n')
            ->orderBy('contactId', 'desc')
            ->get();
    }

    public function updateContact($contactId, $data)
    {
        return DB::table($this->table)
            ->where('contactId', $contactId)
            ->update($data);
    }

    public function countContactsUnread()
    {
        $contacts = DB::table($this->table)
            ->where('isReply', 'n')
            ->orderBy('contactId', 'desc')
            ->get();

        $countUnread = $contacts->count();

        return [
            'countUnread' => $countUnread,
            'contacts'    => $contacts
        ];
    }

    /**
     * Đếm số booking mới chưa xác nhận (bookingStatus = 'b')
     */
    public function countNewBookings()
    {
        return DB::table('tbl_booking')
            ->where('bookingStatus', 'b')
            ->count();
    }

    /**
     * Đếm số user đăng kí mới trong 7 ngày gần nhất
     */
    public function countNewUsers()
    {
        return DB::table('tbl_users')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
    }

    /**
     * Đếm số review mới trong 7 ngày gần nhất
     */
    public function countNewReviews()
    {
        return DB::table('tbl_reviews')
            ->where('timestamp', '>=', now()->subDays(7))
            ->count();
    }

    /**
     * Lấy 5 booking mới nhất chưa xác nhận
     */
    public function getNewBookings()
    {
        return DB::table('tbl_booking')
            ->join('tbl_tours', 'tbl_booking.tourId', '=', 'tbl_tours.tourId')
            ->select('tbl_booking.bookingId', 'tbl_booking.fullName', 'tbl_booking.bookingDate', 'tbl_tours.title as tourTitle')
            ->where('tbl_booking.bookingStatus', 'b')
            ->orderByDesc('tbl_booking.bookingDate')
            ->take(5)
            ->get();
    }
}

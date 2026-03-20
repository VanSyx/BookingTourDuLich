<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BookingModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking';

    public function getBooking()
    {
        return DB::table($this->table)
            ->join('tbl_tours', 'tbl_tours.tourId', '=', 'tbl_booking.tourId')
            ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
            ->select(
                'tbl_booking.*',
                'tbl_tours.title',
                'tbl_booking.fullName',     // 🔥 bắt buộc
                'tbl_booking.email',        // 🔥 bắt buộc
                'tbl_booking.phoneNumber',  // 🔥 bắt buộc
                'tbl_booking.address',    
                'tbl_tours.endDate', // 🔥 thêm dòng này
                'tbl_checkout.paymentMethod',
                'tbl_checkout.paymentStatus'
            )
            ->orderByDesc('tbl_booking.bookingDate')
            ->get();
    }

    public function updateBooking($bookingId, $data){
        return DB::table($this->table)
        ->where('bookingId',$bookingId)
        ->update($data);
    }

    public function getInvoiceBooking($bookingId){

        $invoice = DB::table($this->table)
        ->join('tbl_tours', 'tbl_tours.tourId', '=', 'tbl_booking.tourId')
        ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
        ->where('tbl_booking.bookingId', $bookingId)
        ->first();

        return $invoice;
    }

    public function updateCheckout($bookingId, $data){
        return DB::table('tbl_checkout')
        ->where('bookingId',$bookingId)
        ->update($data);
    }
}

<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking';

    public function createBooking($data)
    {
        // Chèn dữ liệu và trả về ID của bản ghi vừa tạo
        return DB::table($this->table)->insertGetId($data);
    }

    public function cancelBooking($bookingId){
        return DB::table($this->table)
        ->where('bookingId', $bookingId)
        ->update(['bookingStatus' => 'c']);
    }


    public function checkBooking($tourId, $userId)
    {
        return DB::table($this->table)
        ->where('tourId', $tourId)
        ->where('userId', $userId)
        ->where('bookingStatus', 'f')
        ->exists(); // Trả về true nếu bản ghi tồn tại, false nếu không tồn tại
    }

    /**
     * Kiểm tra user đã có booking đang active (chờ xử lý 'b' hoặc đã xác nhận 'y') cho tour này chưa.
     * Nếu có → không được đặt lại.
     */
    public function hasActiveBooking($tourId, $userId)
    {
        return DB::table($this->table)
            ->where('tourId', $tourId)
            ->where('userId', $userId)
            ->whereIn('bookingStatus', ['b', 'y'])
            ->exists();
    }
}

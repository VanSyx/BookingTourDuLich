<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\DB;
>>>>>>> 2bb38fb3467dcaa7830d94d14349521cd7b9c866

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking';
<<<<<<< HEAD
    protected $primaryKey = 'bookingId';

    public $timestamps = false; // 🔥 QUAN TRỌNG

    protected $fillable = [
        'tourId',
        'userId',
        'bookingStatus',
    ];

    const STATUS_FINISHED = 'f';
    const STATUS_CANCELLED = 'c';

    public function createBooking($data)
    {
        return $this->create($data)->bookingId;
    }

    public function cancelBooking($bookingId)
    {
        return $this->where('bookingId', $bookingId)
                    ->update(['bookingStatus' => self::STATUS_CANCELLED]);
    }

    public function checkBooking($tourId, $userId)
    {
        return $this->where('tourId', $tourId)
                    ->where('userId', $userId)
                    ->where('bookingStatus', self::STATUS_FINISHED)
                    ->exists();
    }
}
=======

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
}
>>>>>>> 2bb38fb3467dcaa7830d94d14349521cd7b9c866

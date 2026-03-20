<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking';
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
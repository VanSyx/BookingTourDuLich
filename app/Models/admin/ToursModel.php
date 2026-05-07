<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ToursModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_tours';

    public function getAllTours()
    {
        return DB::table($this->table)
            ->orderBy('tourId', 'DESC')
            ->get();
    }

    public function createTours($data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function uploadImages($data)
    {
        return DB::table('tbl_images')->insert($data);
    }

    public function uploadTempImages($data)
    {
        return DB::table('tbl_temp_images')->insert($data);
    }

    public function addTimeLine($data)
    {
        return DB::table('tbl_timeline')->insert($data);
    }

    public function updateTour($tourId,$data){
        $updated = DB::table($this->table)
        ->where('tourId',$tourId)
        ->update($data);

        return $updated;
    }
    public function deleteTour($tourId)
    {
        // Kiểm tra xem có booking nào đang active không (status 'b' = mới, 'y' = đã xác nhận)
        $activeBooking = DB::table('tbl_booking')
            ->where('tourId', $tourId)
            ->whereIn('bookingStatus', ['b', 'y'])
            ->count();

        if ($activeBooking > 0) {
            return ['success' => false, 'message' => 'Không thể xóa tour vì vẫn còn ' . $activeBooking . ' booking đang hoạt động.'];
        }

        try {
            // Xóa các dữ liệu liên quan trong bảng 'tbl_timeline', 'tbl_images', 'tbl_temp_images'
            DB::table('tbl_timeline')->where('tourId', $tourId)->delete();
            DB::table('tbl_images')->where('tourId', $tourId)->delete();
            DB::table('tbl_temp_images')->where('tourId', $tourId)->delete();

            $deleteTour = DB::table($this->table)->where('tourId', $tourId)->delete();

            // Trả về kết quả xóa tour
            if ($deleteTour) {
                return ['success' => true, 'message' => 'Tour đã được xóa thành công.'];
            } else {
                return ['success' => false, 'message' => 'Không thể xóa tour.'];
            }
        } catch (\Exception $e) {
            // Kiểm tra lỗi do dính dữ liệu khóa ngoại (ví dụ: đã từng có người đặt và hoàn thành)
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Constraint violation') !== false || strpos($e->getMessage(), 'foreign key') !== false) {
                return ['success' => false, 'message' => 'Lỗi: Không thể xóa tour này vì đã có dữ liệu lịch sử đặt tour liên kết với nó!'];
            }
            return ['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()];
        }
    }

    public function getTour($tourId){
        return DB::table($this->table)->where('tourId', $tourId)->first();
    }

    public function getImages($tourId){
        return DB::table('tbl_images')->where('tourId', $tourId)->get();
    }

    public function getTimeLine($tourId){
        return DB::table('tbl_timeline')->where('tourId', $tourId)->get();
    }

    public function deleteData($tourId, $tbl){
        return DB::table($tbl)->where('tourId', $tourId)->delete();
    }

}

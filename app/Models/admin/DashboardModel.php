<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardModel extends Model
{
    use HasFactory;

    public function getSummary()
    {
        $tourWorking = DB::table('tbl_tours')
            ->where('availability', 1)
            ->count();
        $countBooking = DB::table('tbl_booking')
            ->where('bookingStatus', '!=', 'c')
            ->count();
        $totalAmount = DB::table('tbl_checkout')
            ->where('paymentStatus', 'y')
            ->sum('amount');
        // ✅ Số người dùng đã đăng ký tài khoản (không tính tài khoản bị xóa thủnh lý lạc)
        $countUsers = DB::table('tbl_users')->count();

        // Trả về mảng chứa các dữ liệu tổng hợp
        return [
            'tourWorking'  => $tourWorking,
            'countBooking' => $countBooking,
            'totalAmount'  => $totalAmount,
            'countUsers'   => $countUsers,
        ];
    }

    public function getValueDomain()
    {
        // Lấy số lượng tours đang hoạt động cho mỗi miền (b, t, n)
        return DB::table('tbl_tours')
            ->select(DB::raw('domain, COUNT(*) as count'))
            ->where('availability', 1)           // ✅ Chỉ đếm tour đang hiện thị
            ->whereIn('domain', ['b', 't', 'n'])
            ->groupBy('domain')
            ->get()
            ->pluck('count', 'domain');
    }

    public function getValuePayment()
    {
        // ✅ Chỉ tính các giao dịch thuộc booking chưa bị huỷ
        // và có trạng thái thanh toán hợp lệ (y = đã TT, n = chưa TT, r = chờ hoàn)
        return DB::table('tbl_checkout')
            ->join('tbl_booking', 'tbl_checkout.bookingId', '=', 'tbl_booking.bookingId')
            ->select('tbl_checkout.paymentMethod', DB::raw('COUNT(*) as count'))
            ->whereNotIn('tbl_booking.bookingStatus', ['c'])          // Loại booking đã huỷ
            ->whereNotIn('tbl_checkout.paymentStatus', ['c', 'rf'])   // Loại checkout đã cancel hoặc đã hoàn
            ->groupBy('tbl_checkout.paymentMethod')
            ->get()
            ->toArray();
    }

    public function getMostTourBooked()
    {
        return DB::table('tbl_tours')
            ->join('tbl_booking', 'tbl_tours.tourId', '=', 'tbl_booking.tourId')
            ->select('tbl_tours.tourId', 'tbl_tours.title', 'tbl_tours.quantity', DB::raw('SUM(tbl_booking.numAdults + tbl_booking.numChildren) as booked_quantity'))
            ->groupBy('tbl_tours.tourId', 'tbl_tours.quantity', 'tbl_tours.title')
            ->orderByDesc(DB::raw('SUM(tbl_booking.numAdults + tbl_booking.numChildren)')) // Sắp xếp theo số lượng đặt tour giảm dần
            ->take(3) // Lấy 3 tour có số lượng đặt cao nhất
            ->get();
    }

    public function getNewBooking()
    {
        return DB::table('tbl_booking')
            ->join('tbl_tours', 'tbl_booking.tourId', '=', 'tbl_tours.tourId')
            ->where('tbl_booking.bookingStatus', 'b')
            ->orderByDesc('tbl_booking.bookingDate')
            ->select('tbl_booking.*', 'tbl_tours.title as tour_name') // Chọn tất cả các cột từ tbl_booking và thêm tên tour từ tbl_tours
            ->take(3)
            ->get();

    }

    public function getRevenuePerMonth()
    {
        $monthlyRevenue = DB::table('tbl_checkout')
            ->join('tbl_booking', 'tbl_checkout.bookingId', '=', 'tbl_booking.bookingId')
            ->select(DB::raw('MONTH(tbl_booking.bookingDate) as month, SUM(tbl_checkout.amount) as revenue'))
            ->where('tbl_checkout.paymentStatus', 'y')
            ->groupBy(DB::raw('MONTH(tbl_booking.bookingDate)'))
            ->orderBy('month', 'asc')
            ->get();

        // Chuẩn bị mảng doanh thu với 12 tháng
        $revenueData = array_fill(0, 12, 0);  // Mảng chứa doanh thu cho 12 tháng

        // Gán doanh thu cho từng tháng
        foreach ($monthlyRevenue as $data) {
                $revenueData[$data->month - 1] = $data->revenue;  // Gán doanh thu cho tháng tương ứng
        }

        return $revenueData;
    }



}

<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Booking;
use App\Models\clients\Tours;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TourBookedController extends Controller
{
    private $tour;
    private $booking;

    public function __construct()
    {
        $this->tour = new Tours();
        $this->booking = new Booking();
    }
    public function index(Request $req)
    {
        $title = "Tour đã đặt";

        $bookingId = $req->input('bookingId');
        $checkoutId = $req->input('checkoutId');
        $tour_booked = $this->tour->tourBooked($bookingId, $checkoutId);

        // Check if the tour_booked has valid data before accessing properties
        if ($tour_booked && $tour_booked->startDate) {
            $today = Carbon::now();

            $startDate = Carbon::parse($tour_booked->startDate);

            // Calculate the difference in days
            $diffInDays = $startDate->diffInDays($today);

            // Set 'hide' based on the condition
            $hide = $diffInDays < 7 ? 'hide' : '';
        } else {
            $hide = '';
        }

        // dd($tour_booked);
        return view("clients.tour-booked", compact('title', 'tour_booked', 'hide', 'bookingId'));
    }

    public function cancelBooking(Request $req)
    {
        $tourId = $req->tourId;
        $quantityAdults = $req->quantity__adults;
        $quantityChildren = $req->quantity__children;
        $bookingId = $req->bookingId;

        $tour = $this->tour->getTourDetail($tourId);
        
        // --- THÊM LOGIC PHẠT VÀ TÍNH THỜI GIAN ---
        $today = Carbon::now();
        $startDate = Carbon::parse($tour->startDate);
        $diffInDays = $today->diffInDays($startDate, false); // false giữ giá trị âm nếu đã qua ngày

        // Hủy dưới 3 ngày: Không cho phép
        if ($diffInDays < 3) {
            toastr()->error('Khởi hành dưới 3 ngày. Không thể tự hủy, xin vui lòng gọi hotline.', 'Từ chối hủy');
            return redirect()->back();
        }

        $currentQuantity = $tour->quantity;
        // Tính toán số lượng trả lại
        $return_quantity = $quantityAdults + $quantityChildren;

        // Cập nhật lại số lượng mới cho tour
        $newQuantity = $currentQuantity + $return_quantity;
        $updateQuantity = $this->tour->updateTours($tourId, ['quantity' => $newQuantity]);

        // Hủy booking
        $updateBooking = $this->booking->cancelBooking($bookingId);

        // --- CẬP NHẬT TRẠNG THÁI THANH TOÁN (HOÀN TIỀN) ---
        $checkout = \Illuminate\Support\Facades\DB::table('tbl_checkout')
            ->where('bookingId', $bookingId)
            ->first();

        $penaltyMessage = '';
        if ($checkout) {
            if ($checkout->paymentStatus === 'y') {
                // Đã thanh toán qua MoMo/Paypal -> Chuyển thành Pending Refund ('r')
                \Illuminate\Support\Facades\DB::table('tbl_checkout')
                    ->where('bookingId', $bookingId)
                    ->update(['paymentStatus' => 'r']);
                
                if ($diffInDays >= 3 && $diffInDays < 7) {
                    $penaltyMessage = ' (Bạn chịu phí phạt 50%. Quý khách sẽ sớm nhận lại tiền qua CSKH).';
                } else {
                    $penaltyMessage = ' (Hủy đúng hạn. Quý khách sẽ được hoàn 100% tiền vé qua CSKH).';
                }
            } else {
                // Chưa cọc/Chưa thanh toán -> Đổi thành mốc Cancel ('c')
                \Illuminate\Support\Facades\DB::table('tbl_checkout')
                    ->where('bookingId', $bookingId)
                    ->update(['paymentStatus' => 'c']);
            }
        }

        if ($updateQuantity && $updateBooking) {
            toastr()->success('Hủy thành công!' . $penaltyMessage, 'Thông báo');
        } else {
            toastr()->error('Có lỗi xảy ra !', 'Thông báo');
        }

        return redirect()->back();
    }
}

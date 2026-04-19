<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingCancellation;
use App\Models\admin\BookingModel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingManagementController extends Controller
{

    private $booking;

    public function __construct()
    {
        $this->booking = new BookingModel();
    }
    public function index()
    {
        $title = 'Quản lý đặt Tour';

        $list_booking = $this->booking->getBooking();
        $list_booking = $this->updateHideBooking($list_booking);

        // dd($list_booking);

        return view('admin.booking', compact('title', 'list_booking'));
    }

    public function confirmBooking(Request $request)
    {
        $bookingId = $request->bookingId;

        $dataConfirm = [
            'bookingStatus' => 'y'
        ];

        $result = $this->booking->updateBooking($bookingId, $dataConfirm);

        if ($result) {
            $list_booking = $this->booking->getBooking();
            $list_booking = $this->updateHideBooking($list_booking);
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.',
                'data' => view('admin.partials.list-booking', compact('list_booking'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thất bại.'
            ], 500);
        }
    }

    public function showDetail($bookingId)
    {
        $title = 'Chi tiết đơn đặt';

        $invoice_booking = $this->booking->getInvoiceBooking($bookingId);
        // dd($invoice_booking);
        $hide='hide';
        if ($invoice_booking->transactionId == null) {
            $invoice_booking->transactionId = 'Thanh toán tại công ty Travela';
        }
        if ($invoice_booking->paymentStatus === 'n') {
            $hide = '';
        }
        return view('admin.booking-detail', compact('title', 'invoice_booking','hide'));
    }


    public function sendPdf(Request $request)
    {
        $bookingId = $request->input('bookingId');
        $email = $request->input('email');
        $title = 'Hóa đơn';
        $invoice_booking = $this->booking->getInvoiceBooking($bookingId);

        if ($invoice_booking->transactionId == null) {
            $invoice_booking->transactionId = 'Thanh toán tại công ty Travela';
        }

        try {
            Mail::send('admin.emails.invoice', compact('invoice_booking'), function ($message) use ($invoice_booking) {
                $message->to($invoice_booking->email)
                    ->subject('Hóa đơn đặt tour của khách hàng' . $invoice_booking->fullName);
            });

            return response()->json([
                'success' => true,
                'message' => 'Hóa đơn đã được gửi qua email thành công.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi email: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function finishBooking(Request $request)
    {
        $bookingId = $request->bookingId;

        // --- VALIDATION: PHƯƠNG ÁN 2 ---
        $bookingDetail = $this->booking->getInvoiceBooking($bookingId);
        
        if (!$bookingDetail) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy thông tin Booking!'], 404);
        }

        // Validate 1: Payment Status
        if ($bookingDetail->paymentStatus != 'y') {
            return response()->json(['success' => false, 'message' => 'Lỗi: Khách hàng chưa hoàn tất thanh toán! Không thể quyết toán.'], 400);
        }

        // Validate 2: Date
        $endDate = Carbon::parse($bookingDetail->endDate)->endOfDay();
        if (Carbon::now()->lessThan($endDate)) {
            return response()->json(['success' => false, 'message' => 'Lỗi: Chuyến đi chưa kết thúc (Ngày về: ' . $endDate->format('d/m/Y') . ')! Cần chờ sau chuyến đi để quyết toán.'], 400);
        }

        $dataConfirm = [
            'bookingStatus' => 'f'
        ];

        $result = $this->booking->updateBooking($bookingId, $dataConfirm);

        if ($result) {
            $list_booking = $this->booking->getBooking();
            $list_booking = $this->updateHideBooking($list_booking);
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.',
                'data' => view('admin.partials.list-booking', compact('list_booking'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thất bại.'
            ], 500);
        }
    }

    public function receiviedMoney(Request $request){
        $bookingId = $request->bookingId;

        $dataUpdate = [
            'paymentStatus' => 'y'
        ];

        $result = $this->booking->updateCheckout($bookingId, $dataUpdate);

        if ($result) {

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thất bại.'
            ], 500);
        }
    }

    public function refundedMoney(Request $request){
        $bookingId = $request->bookingId;

        $dataUpdate = [
            'paymentStatus' => 'rf' // Refunded
        ];

        $result = $this->booking->updateCheckout($bookingId, $dataUpdate);

        if ($result) {
            $list_booking = $this->booking->getBooking();
            $list_booking = $this->updateHideBooking($list_booking);
            return response()->json([
                'success' => true,
                'message' => 'Đã hoàn tiền cho khách thành công.',
                'data' => view('admin.partials.list-booking', compact('list_booking'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thất bại.'
            ], 500);
        }
    }

    /**
     * Admin huỷ booking: cập nhật bookingStatus='c', hoàn lại slot, xử lý hoàn tiền.
     */
    public function cancelBooking(Request $request)
    {
        $bookingId = $request->bookingId;

        // Lấy thông tin booking để hoàn slot và xử lý thanh toán
        $booking = $this->booking->getInvoiceBooking($bookingId);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy booking.'], 404);
        }

        // Không cho phép huỷ booking đã hoàn thành hoặc đã huỷ rồi
        if (in_array($booking->bookingStatus, ['f', 'c'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể huỷ booking đã hoàn thành hoặc đã huỷ.'
            ], 422);
        }

        // 1. Cập nhật bookingStatus = 'c'
        $this->booking->updateBooking($bookingId, ['bookingStatus' => 'c']);

        // 2. Hoàn lại số lượng chỗ cho tour
        $returnQty = ($booking->numAdults ?? 0) + ($booking->numChildren ?? 0);
        if ($returnQty > 0 && isset($booking->tourId)) {
            DB::table('tbl_tours')
                ->where('tourId', $booking->tourId)
                ->increment('quantity', $returnQty);
        }

        // 3. Xử lý trạng thái thanh toán
        $refundMessage = '';
        if ($booking->paymentStatus === 'y') {
            // Đã thanh toán → chuyển sang Pending Refund ('r')
            $this->booking->updateCheckout($bookingId, ['paymentStatus' => 'r']);
            $refundMessage = ' Booking đã được thanh toán — vui lòng hoàn tiền thủ công cho khách.';
        } elseif ($booking->paymentStatus === 'n') {
            // Chưa thanh toán → huỷ
            $this->booking->updateCheckout($bookingId, ['paymentStatus' => 'c']);
        }

        $list_booking = $this->booking->getBooking();
        $list_booking = $this->updateHideBooking($list_booking);

        // 4. Gửi email thông báo huỷ cho user
        try {
            $user = DB::table('tbl_users')->where('userId', $booking->userId)->first();
            $tour = DB::table('tbl_tours')->where('tourId', $booking->tourId)->first();

            if ($user && $user->email && $tour) {
                Mail::to($user->email)->send(new BookingCancellation(
                    (array) $booking,
                    (array) $tour,
                    (array) $user,
                    'admin'
                ));
                \Log::info('Cancellation email sent by admin', [
                    'bookingId' => $bookingId,
                    'email'     => $user->email,
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Cancellation email failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã huỷ booking thành công.' . $refundMessage,
            'data'    => view('admin.partials.list-booking', compact('list_booking'))->render()
        ]);
    }

    private function updateHideBooking($list_booking)
    {
        // Lấy ngày hiện tại
        $currentDate = date('Y-m-d');

        foreach ($list_booking as $booking) {
            // Nút "Hoàn thành" CHỈ hiển thị khi:
            // 1. Tour đã kết thúc (hoặc là ngày cuối cùng)
            // 2. Booking chưa bị huỷ ('c') và chưa hoàn thành ('f'), phải ở trạng thái được xác nhận ('y')
            // 3. Khách hàng đã thanh toán xong ('y')
            if ($booking->endDate <= $currentDate && $booking->bookingStatus == 'y' && $booking->paymentStatus == 'y') {
                $hide = '';
            } else {
                $hide = 'hide';
            }

            // Gán giá trị $hide vào mỗi booking
            $booking->hide = $hide;
        }

        return $list_booking;
    }

}

<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Booking;
use App\Models\clients\Checkout;
use App\Models\clients\Tours;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    private $tour;
    private $booking;
    private $checkout;

    public function __construct()
    {
        parent::__construct(); // Gọi constructor của Controller để khởi tạo $user
        $this->tour = new Tours();
        $this->booking = new Booking();
        $this->checkout = new Checkout();
    }

    public function index($id)
    {

        $title = 'Đặt Tour';
        $tour = $this->tour->getTourDetail($id);

        if (!$tour) {
            return redirect()->route('tours')->with('error', 'Không tìm thấy thông tin tour.');
        }

        $transIdMomo = null; // Initialize the variable

        // ✅ NEW: Get PayPal Client ID for frontend SDK
        $paypalMode = config('paypal.mode', 'sandbox');
        $paypalClientId = config('paypal.' . $paypalMode . '.client_id', '');

        return view('clients.booking', compact('title', 'tour', 'transIdMomo', 'paypalClientId'));
    }

    // Booking theo lịch khởi hành cụ thể (từ calendar)
    public function bookingBySchedule($scheduleId)
    {
        $schedule = DB::table('tbl_tour_schedules')->where('scheduleId', $scheduleId)->first();

        if (!$schedule) {
            return redirect()->route('tours')->with('error', 'Không tìm thấy lịch khởi hành.');
        }

        $tour = $this->tour->getTourDetail($schedule->tourId);
        if (!$tour) {
            return redirect()->route('tours')->with('error', 'Không tìm thấy thông tin tour.');
        }

        $title = 'Đặt Tour — ' . $tour->title;
        $transIdMomo = null;
        $paypalMode = config('paypal.mode', 'sandbox');
        $paypalClientId = config('paypal.' . $paypalMode . '.client_id', '');

        return view('clients.booking', compact('title', 'tour', 'schedule', 'transIdMomo', 'paypalClientId'));
    }

    public function validateBooking(Request $req)
    {
        $numAdults = (int) $req->input('numAdults');
        $numChildren = (int) $req->input('numChildren');
        $tourId = $req->input('tourId');
        $userId = $this->getUserId();

        // 1. Kiểm tra user đã đăng nhập chưa
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để đặt tour.'], 401);
        }

        // 2. Lấy thông tin tour để kiểm tra
        $tour = $this->tour->getTourDetail($tourId);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Tour không tồn tại.'], 404);
        }

        // 3. Kiểm tra ngày khởi hành tour
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $startDate = Carbon::parse($tour->startDate)->startOfDay();
        if ($now->greaterThan($startDate)) {
            return response()->json(['success' => false, 'message' => 'Tour này đã khởi hành vào ngày ' . $startDate->format('d/m/Y') . '. Không thể đặt tour đã qua ngày khởi hành.'], 422);
        }

        // 4. Kiểm tra số chỗ còn lại
        $totalPassengers = $numAdults + $numChildren;
        if ($totalPassengers <= 0) {
            return response()->json(['success' => false, 'message' => 'Số lượng hành khách không hợp lệ.'], 422);
        }
        if ($tour->quantity < $totalPassengers) {
            return response()->json(['success' => false, 'message' => 'Tour không còn đủ chỗ. Chỉ còn ' . $tour->quantity . ' chỗ trống.'], 422);
        }

        // 5. Kiểm tra user đã có booking active cho tour này chưa
        if ($this->booking->hasActiveBooking($tourId, $userId)) {
            return response()->json(['success' => false, 'message' => 'Bạn đã có đơn đặt tour này đang chờ xử lý hoặc đã được xác nhận. Vui lòng kiểm tra lịch sử đặt tour.'], 422);
        }

        // 6. Kiểm tra mã giảm giá (nếu có)
        $couponCode = $req->input('coupon_code');
        if (!empty($couponCode)) {
            $promotion = DB::table('tbl_promotions')
                ->where('code', $couponCode)
                ->where('isActive', 1)
                ->where('startDate', '<=', now())
                ->where('endDate', '>=', now())
                ->where('quantity', '>', 0)
                ->first();

            if (!$promotion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng.'
                ], 422);
            }
        }

        return response()->json(['success' => true]);
    }

    public function createBooking(Request $req)
    {
        $address = $req->input('address');
        $email = $req->input('email');
        $fullName = $req->input('fullName');
        $numAdults = (int) $req->input('numAdults');
        $numChildren = (int) $req->input('numChildren');
        $paymentMethod = $req->input('payment_hidden');
        $tel = $req->input('tel');
        $totalPrice = $req->input('totalPrice');
        $tourId = $req->input('tourId');
        $userId = $this->getUserId();

        // =========================================================
        // KIỂM TRA LOGIC NGHIỆP VỤ LẦN CUỐI
        // =========================================================

        // 1. Kiểm tra user đã đăng nhập chưa
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để đặt tour.'], 401);
        }

        // 2. Lấy thông tin tour để kiểm tra
        $tour = $this->tour->getTourDetail($tourId);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Tour không tồn tại.'], 404);
        }

        // 3. Kiểm tra ngày khởi hành tour
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $startDate = Carbon::parse($tour->startDate)->startOfDay();
        if ($now->greaterThan($startDate)) {
            return response()->json(['success' => false, 'message' => 'Tour này đã khởi hành vào ngày ' . $startDate->format('d/m/Y') . '. Không thể đặt tour đã qua ngày khởi hành.'], 422);
        }

        // 4. Kiểm tra số chỗ còn lại
        $totalPassengers = $numAdults + $numChildren;
        if ($totalPassengers <= 0) {
            return response()->json(['success' => false, 'message' => 'Số lượng hành khách không hợp lệ.'], 422);
        }
        if ($tour->quantity < $totalPassengers) {
            return response()->json(['success' => false, 'message' => 'Tour không còn đủ chỗ. Chỉ còn ' . $tour->quantity . ' chỗ trống.'], 422);
        }

        // 5. Kiểm tra user đã có booking active cho tour này chưa
        if ($this->booking->hasActiveBooking($tourId, $userId)) {
            return response()->json(['success' => false, 'message' => 'Bạn đã có đơn đặt tour này đang chờ xử lý hoặc đã được xác nhận. Vui lòng kiểm tra lịch sử đặt tour.'], 422);
        }

        // =========================================================
        // ✅ FIX #3: PROCESS PROMOTION CODE
        // =========================================================
        $couponCode = $req->input('coupon_code'); // Get coupon from form
        $appliedPromotionId = null;
        $appliedDiscount = 0;

        if (!empty($couponCode)) {
            // ✅ STEP 1: Find promotion in database
            $promotion = DB::table('tbl_promotions')
                ->where('code', $couponCode)
                ->where('isActive', 1)
                ->where('startDate', '<=', now())
                ->where('endDate', '>=', now())
                ->where('quantity', '>', 0)
                ->first();

            if (!$promotion) {
                // ✅ Invalid coupon → reject booking
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng.'
                ], 422);
            }

            // ✅ STEP 2: Calculate discount
            $appliedPromotionId = $promotion->promotionId;

            if ($promotion->discountPercent > 0) {
                // Percentage discount
                $appliedDiscount = ($totalPrice * $promotion->discountPercent) / 100;
            } else if ($promotion->discountAmount > 0) {
                // Fixed amount discount
                $appliedDiscount = $promotion->discountAmount;
            }

            // ✅ STEP 3: Apply discount (ensure >= 0)
            $totalPrice = max(0, $totalPrice - $appliedDiscount);

            // ✅ STEP 4: Decrement usage count
            DB::table('tbl_promotions')
                ->where('promotionId', $promotion->promotionId)
                ->decrement('quantity');

            // ✅ LOG for audit
            \Log::info("Promotion applied", [
                'code' => $couponCode,
                'discount' => $appliedDiscount,
                'finalPrice' => $totalPrice,
                'userId' => $userId,
                'bookingDate' => now()
            ]);
        }

        // =========================================================
        // XỬ LÝ BOOKING VÀ CHECKOUT
        // =========================================================
        $dataBooking = [
            'tourId' => $tourId,
            'userId' => $userId,
            'address' => $address,
            'fullName' => $fullName,
            'email' => $email,
            'numAdults' => $numAdults,
            'numChildren' => $numChildren,
            'phoneNumber' => $tel,
            'totalPrice' => $totalPrice,
            'promotionId' => $appliedPromotionId,
            'bookingDate' => now()->toDateTimeString(), // ✅ Lưu ngày đặt
        ];

        $bookingId = $this->booking->createBooking($dataBooking);

        // ✅ FIX #2: CORRECTED payment status logic
        $paymentStatus = 'n'; // Default: unpaid

        if ($paymentMethod === 'paypal-payment') {
            // ✅ PayPal: Transaction already captured by frontend SDK + onApprove callback
            // Safe to mark as 'y' if we have valid transactionId
            if (!empty($req->transactionIdPaypal)) {
                $paymentStatus = 'y';
            }
        } elseif ($paymentMethod === 'momo-payment') {
            // ✅ MoMo: Trạng thái này chỉ được gọi (auto-submit form) khi callback MoMo trả về resultCode = 0 (Thành công)
            // Do đó nếu có transactionIdMomo, ta có thể đánh dấu là đã thanh toán
            if (!empty($req->transactionIdMomo)) {
                $paymentStatus = 'y';
            }
        }

        // ✅ Cash: Admin confirms payment manually later
        // Keep as 'n' until admin marks received

        $dataCheckout = [
            'bookingId'     => $bookingId,
            'paymentMethod' => $paymentMethod,
            'amount'        => $totalPrice,
            'paymentStatus' => $paymentStatus,
        ];

        // Ghi log transactionId để đối chiếu nếu cần (cột không có trong DB)
        if ($paymentMethod === 'paypal-payment' && !empty($req->transactionIdPaypal)) {
            \Log::info('PayPal transactionId: ' . $req->transactionIdPaypal . ' bookingId: ' . $bookingId);
        } elseif ($paymentMethod === 'momo-payment' && !empty($req->transactionIdMomo)) {
            \Log::info('MoMo transactionId: ' . $req->transactionIdMomo . ' bookingId: ' . $bookingId);
        }

        $checkoutId = $this->checkout->createCheckout($dataCheckout);

        if (!$bookingId || !$checkoutId) {
            return response()->json(['success' => false, 'message' => 'Có vấn đề khi tạo đơn đặt tour. Vui lòng thử lại.'], 500);
        }

        // Cập nhật số lượng chỗ còn lại của tour
        $dataUpdate = [
            'quantity' => $tour->quantity - $totalPassengers
        ];
        $this->tour->updateTours($tourId, $dataUpdate);

        // Gửi email xác nhận booking cho user
        try {
            $user = DB::table('tbl_users')->where('userId', $userId)->first();

            if ($user && $user->email) {
                // Email xác nhận đặt tour
                Mail::to($user->email)->send(
                    new BookingConfirmation(
                        array_merge((array) $dataBooking, ['bookingId' => $bookingId]),
                        (array) $tour,
                        (array) $user
                    )
                );
                \Log::info('Booking confirmation email sent', [
                    'bookingId' => $bookingId,
                    'email' => $user->email,
                    'tour' => $tour->title,
                ]);

                // Email xác nhận thanh toán nếu đã thanh toán (PayPal)
                if ($paymentStatus === 'y') {
                    Mail::to($user->email)->send(
                        new PaymentConfirmation(
                            array_merge((array) $dataBooking, ['bookingId' => $bookingId]),
                            $dataCheckout,
                            (array) $tour,
                            (array) $user
                        )
                    );
                    \Log::info('Payment confirmation email sent', [
                        'bookingId' => $bookingId,
                        'email' => $user->email,
                        'paymentMethod' => $paymentMethod,
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Email sending failed', [
                'bookingId' => $bookingId,
                'error' => $e->getMessage(),
            ]);
            // Email lỗi không ảnh hưởng booking
        }

        // Trả về JSON success để JS xử lý redirect
        return response()->json([
            'success' => true,
            'message' => 'Đặt tour thành công!',
            'bookingId' => $bookingId,
            'checkoutId' => $checkoutId,
            'redirectUrl' => route('tour-booked', ['bookingId' => $bookingId, 'checkoutId' => $checkoutId]),
        ]);
    }

    public function createMomoPayment(Request $request)
    {
        // Lưu tourId và toàn bộ booking form data vào session để restore sau callback
        session()->put('tourId', $request->tourId);
        session()->put('momoBookingData', [
            'tourId' => $request->tourId,
            'fullName' => $request->fullName,
            'email' => $request->email,
            'tel' => $request->tel,
            'address' => $request->address,
            'numAdults' => $request->numAdults,
            'numChildren' => $request->numChildren,
            'totalPrice' => $request->input('totalPrice') ?? $request->input('amount'),
            'payment_hidden' => $request->payment_hidden ?? 'momo-payment',
        ]);

        try {
            // Lấy amount từ request, nếu không có thì mặc định 10000 (đơn vị VNĐ)
            $amount = $request->input('amount', 10000);
            // MoMo yêu cầu amount là số nguyên VNĐ
            $amount = (int) $amount;

            // Các thông tin cần thiết của MoMo
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = "MOMOBKUN20180529"; // mã partner của bạn
            $accessKey = "klm05TvNBzhg7h7j"; // access key của bạn
            $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa"; // secret key của bạn

            $orderInfo = "Thanh toán đơn hàng";
            $requestId = time();
            $orderId = time();
            $extraData = "";
            $redirectUrl = url('/booking'); // URL chuyển hướng động
            $ipnUrl = url('/booking');      // URL IPN động
            $requestType = 'payWithATM'; // Kiểu yêu cầu

            // Tạo rawHash và chữ ký theo cách thủ công
            $rawHash = "accessKey=" . $accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $partnerCode .
                "&redirectUrl=" . $redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=" . $requestType;

            // Tạo chữ ký
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            // Dữ liệu gửi đến MoMo
            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test", // Tên đối tác
                'storeId' => "MomoTestStore", // ID cửa hàng
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];

            // Gửi yêu cầu POST đến MoMo để tạo yêu cầu thanh toán
            $response = Http::post($endpoint, $data);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['payUrl'])) {
                    return response()->json(['payUrl' => $body['payUrl']]);
                } else {
                    // Trả về thông tin lỗi trong response nếu không có 'payUrl'
                    return response()->json(['error' => 'Invalid response from MoMo', 'details' => $body], 400);
                }
            } else {
                // Trả về thông tin lỗi trong response nếu lỗi kết nối
                return response()->json(['error' => 'Lỗi kết nối với MoMo', 'details' => $response->body()], 500);
            }
        } catch (\Exception $e) {
            // Trả về chi tiết ngoại lệ trong response
            return response()->json(['error' => 'Đã xảy ra lỗi', 'message' => $e->getMessage()], 500);
        }
    }


    public function handlePaymentMomoCallback(Request $request)
    {
        $resultCode = $request->input('resultCode');
        $transIdMomo = $request->query('transId');

        $tourId = session()->get('tourId');
        $momoBookingData = session()->get('momoBookingData', []);

        // Xóa session ngay để tránh tạo booking trùng khi reload
        session()->forget(['tourId', 'momoBookingData']);

        if ($resultCode != '0') {
            // Thanh toán thất bại → redirect về trang tours với lỗi
            return redirect()->route('tours')
                ->with('error', 'Thanh toán MoMo thất bại (mã lỗi: ' . $resultCode . '). Vui lòng thử lại.');
        }

        // ── Thanh toán thành công ──────────────────────────────────────
        $tour = $this->tour->getTourDetail($tourId ?? ($momoBookingData['tourId'] ?? null));

        if (!$tour || empty($momoBookingData)) {
            return redirect()->route('tours')
                ->with('error', 'Phiên giao dịch đã hết hạn. Vui lòng đặt tour lại.');
        }

        $userId = $this->getUserId();
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để hoàn tất đặt tour.');
        }

        // Kiểm tra đã có booking trùng chưa (tránh tạo đôi khi callback gọi 2 lần)
        if ($this->booking->hasActiveBooking($tour->tourId, $userId)) {
            // Lấy booking cũ nhất để redirect đến đó
            $existingBooking = DB::table('tbl_booking')
                ->where('tourId', $tour->tourId)
                ->where('userId', $userId)
                ->orderBy('bookingId', 'desc')
                ->first();
            $existingCheckout = $existingBooking
                ? DB::table('tbl_checkout')->where('bookingId', $existingBooking->bookingId)->first()
                : null;
            if ($existingBooking && $existingCheckout) {
                return redirect()->route('tour-booked', [
                    'bookingId'  => $existingBooking->bookingId,
                    'checkoutId' => $existingCheckout->checkoutId,
                ]);
            }
        }

        // Tạo booking mới
        $numAdults    = (int) ($momoBookingData['numAdults'] ?? 1);
        $numChildren  = (int) ($momoBookingData['numChildren'] ?? 0);
        $totalPrice   = (float) ($momoBookingData['totalPrice'] ?? 0);

        $dataBooking = [
            'tourId'      => $tour->tourId,
            'userId'      => $userId,
            'address'     => $momoBookingData['address'] ?? '',
            'fullName'    => $momoBookingData['fullName'] ?? '',
            'email'       => $momoBookingData['email'] ?? '',
            'numAdults'   => $numAdults,
            'numChildren' => $numChildren,
            'phoneNumber' => $momoBookingData['tel'] ?? '',
            'totalPrice'  => $totalPrice,
            'bookingDate' => now()->toDateTimeString(),
        ];

        $bookingId = $this->booking->createBooking($dataBooking);

        $dataCheckout = [
            'bookingId'     => $bookingId,
            'paymentMethod' => 'momo-payment',
            'amount'        => $totalPrice,
            'paymentStatus' => 'y', // MoMo đã xác nhận thanh toán thành công
        ];
        $checkoutId = $this->checkout->createCheckout($dataCheckout);

        // Cập nhật số chỗ còn lại
        $this->tour->updateTours($tour->tourId, [
            'quantity' => max(0, $tour->quantity - ($numAdults + $numChildren))
        ]);

        // Gửi email xác nhận
        try {
            $user = DB::table('tbl_users')->where('userId', $userId)->first();
            if ($user && $user->email) {
                Mail::to($user->email)->send(new BookingConfirmation(
                    array_merge((array) $dataBooking, ['bookingId' => $bookingId]),
                    (array) $tour,
                    (array) $user
                ));
                Mail::to($user->email)->send(new PaymentConfirmation(
                    array_merge((array) $dataBooking, ['bookingId' => $bookingId]),
                    $dataCheckout,
                    (array) $tour,
                    (array) $user
                ));
            }
        } catch (\Exception $e) {
            \Log::error('MoMo callback email error: ' . $e->getMessage());
        }

        return redirect()->route('tour-booked', [
            'bookingId'  => $bookingId,
            'checkoutId' => $checkoutId,
        ]);
    }

    //Kiểm tra người dùng đã đặt và hoàn thành tour hay chưa để đánh giá
    public function checkBooking(Request $req)
    {
        $tourId = $req->tourId;
        $userId = $this->getUserId();
        $check = $this->booking->checkBooking($tourId, $userId);
        if (!$check) {
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => true]);
    }

}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourScheduleController extends Controller
{
    public function index(Request $request)
    {
        $tourId = $request->query('tourId');
        $tours  = DB::table('tbl_tours')->orderBy('title')->get();

        $schedules = collect();
        $selectedTour = null;

        if ($tourId) {
            $schedules    = DB::table('tbl_tour_schedules')
                ->where('tourId', $tourId)
                ->orderBy('startDate')
                ->get();
            $selectedTour = DB::table('tbl_tours')->where('tourId', $tourId)->first();
        }

        $title = 'Quản lý lịch khởi hành';
        return view('admin.tour-schedules', compact('title', 'tours', 'schedules', 'selectedTour', 'tourId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tourId'     => 'required|integer',
            'startDate'  => 'required|date',
            'endDate'    => 'required|date|after_or_equal:startDate',
            'priceAdult' => 'required|numeric|min:0',
            'priceChild' => 'required|numeric|min:0',
            'quantity'   => 'required|integer|min:1',
        ]);

        DB::table('tbl_tour_schedules')->insert([
            'tourId'     => $request->tourId,
            'startDate'  => $request->startDate,
            'endDate'    => $request->endDate,
            'priceAdult' => $request->priceAdult,
            'priceChild' => $request->priceChild,
            'quantity'   => $request->quantity,
            'note'       => $request->note,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.tour-schedules', ['tourId' => $request->tourId])
            ->with('success', 'Thêm lịch khởi hành thành công!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'scheduleId' => 'required|integer',
            'startDate'  => 'required|date',
            'endDate'    => 'required|date|after_or_equal:startDate',
            'priceAdult' => 'required|numeric|min:0',
            'priceChild' => 'required|numeric|min:0',
            'quantity'   => 'required|integer|min:0',
        ]);

        DB::table('tbl_tour_schedules')
            ->where('scheduleId', $request->scheduleId)
            ->update([
                'startDate'  => $request->startDate,
                'endDate'    => $request->endDate,
                'priceAdult' => $request->priceAdult,
                'priceChild' => $request->priceChild,
                'quantity'   => $request->quantity,
                'note'       => $request->note,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.tour-schedules', ['tourId' => $request->tourId])
            ->with('success', 'Cập nhật lịch khởi hành thành công!');
    }

    public function destroy(Request $request)
    {
        DB::table('tbl_tour_schedules')->where('scheduleId', $request->scheduleId)->delete();

        return redirect()->route('admin.tour-schedules', ['tourId' => $request->tourId])
            ->with('success', 'Xóa lịch khởi hành thành công!');
    }

    // API: trả JSON lịch theo tour (dùng cho calendar phía client)
    public function apiGetSchedules($tourId)
    {
        $schedules = DB::table('tbl_tour_schedules')
            ->where('tourId', $tourId)
            ->where('quantity', '>', 0)
            ->where('startDate', '>=', now()->toDateString())
            ->orderBy('startDate')
            ->get();

        return response()->json($schedules);
    }

    // API: trả JSON chi tiết 1 lịch
    public function apiGetScheduleDetail($scheduleId)
    {
        $schedule = DB::table('tbl_tour_schedules as s')
            ->join('tbl_tours as t', 's.tourId', '=', 't.tourId')
            ->where('s.scheduleId', $scheduleId)
            ->select('s.*', 't.title', 't.destination', 't.time', 't.duration')
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'Không tìm thấy lịch khởi hành'], 404);
        }

        return response()->json($schedule);
    }
}

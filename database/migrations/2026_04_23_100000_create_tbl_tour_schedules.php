<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_tour_schedules', function (Blueprint $table) {
            $table->increments('scheduleId');
            $table->integer('tourId');
            $table->date('startDate');
            $table->date('endDate');
            $table->decimal('priceAdult', 12, 2)->nullable();
            $table->decimal('priceChild', 12, 2)->nullable();
            $table->integer('quantity')->default(100);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('tourId')->references('tourId')->on('tbl_tours')->onDelete('cascade');
        });

        // Seed: Tạo lịch khởi hành mẫu cho các tour hiện có
        $now = now();
        DB::table('tbl_tour_schedules')->insert([
            // Tour 6 (ben - Đà Nẵng)
            ['tourId' => 6, 'startDate' => '2026-05-10', 'endDate' => '2026-05-20', 'priceAdult' => 150000, 'priceChild' => 75000, 'quantity' => 20, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
            ['tourId' => 6, 'startDate' => '2026-05-25', 'endDate' => '2026-06-04', 'priceAdult' => 160000, 'priceChild' => 80000, 'quantity' => 15, 'note' => 'Khuyến mãi cuối tháng', 'created_at' => $now, 'updated_at' => $now],
            ['tourId' => 6, 'startDate' => '2026-06-15', 'endDate' => '2026-06-25', 'priceAdult' => 150000, 'priceChild' => 75000, 'quantity' => 25, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
            // Tour 7 (nhanvienmoiden - Đà Nẵng)
            ['tourId' => 7, 'startDate' => '2026-05-05', 'endDate' => '2026-05-08', 'priceAdult' => 1000000, 'priceChild' => 500000, 'quantity' => 30, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
            ['tourId' => 7, 'startDate' => '2026-05-20', 'endDate' => '2026-05-23', 'priceAdult' => 1100000, 'priceChild' => 550000, 'quantity' => 20, 'note' => 'Lễ 30/4 - 1/5', 'created_at' => $now, 'updated_at' => $now],
            ['tourId' => 7, 'startDate' => '2026-06-10', 'endDate' => '2026-06-13', 'priceAdult' => 1000000, 'priceChild' => 500000, 'quantity' => 35, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
            // Tour 8 (Kỳ Co - Bình Định)
            ['tourId' => 8, 'startDate' => '2026-05-15', 'endDate' => '2026-05-18', 'priceAdult' => 1500000, 'priceChild' => 750000, 'quantity' => 25, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
            ['tourId' => 8, 'startDate' => '2026-05-28', 'endDate' => '2026-05-31', 'priceAdult' => 1600000, 'priceChild' => 800000, 'quantity' => 20, 'note' => 'Mùa hè', 'created_at' => $now, 'updated_at' => $now],
            ['tourId' => 8, 'startDate' => '2026-06-20', 'endDate' => '2026-06-23', 'priceAdult' => 1500000, 'priceChild' => 750000, 'quantity' => 30, 'note' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_tour_schedules');
    }
};

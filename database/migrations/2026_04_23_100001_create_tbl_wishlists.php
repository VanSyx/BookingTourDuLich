<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_wishlists', function (Blueprint $table) {
            $table->increments('wishlistId');
            $table->integer('tourId');
            $table->integer('userId');
            $table->timestamp('created_at')->nullable();

            $table->foreign('tourId')->references('tourId')->on('tbl_tours')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_wishlists');
    }
};

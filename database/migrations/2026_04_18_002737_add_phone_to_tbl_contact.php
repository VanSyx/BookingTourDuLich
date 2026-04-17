<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbl_contact', function (Blueprint $table) {
            $table->string('phoneNumber', 20)->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('tbl_contact', function (Blueprint $table) {
            $table->dropColumn('phoneNumber');
        });
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRfidToTableBoxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('boxes','rfid'))
        Schema::table('boxes', function (Blueprint $table) {
            $table->text('rfid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('boxes','rfid'))
        Schema::table('boxes', function (Blueprint $table) {
            $table->dropColumn('rfid');
        });
    }
}

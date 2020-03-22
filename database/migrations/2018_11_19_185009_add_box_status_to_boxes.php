<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBoxStatusToBoxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boxes', function(Blueprint $table) {
        	$table->enum('box_status', ['NEW', 'DATA_ENTRY', 'CLOSED'])->after('has_pending_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boxes', function(Blueprint $table) {
        	$table->dropColumn('box_status');
        });
    }
}

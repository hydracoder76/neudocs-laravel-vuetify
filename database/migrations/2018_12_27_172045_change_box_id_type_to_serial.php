<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
/**
 * Class ChangeBoxIdTypeToSerial
 */
class ChangeBoxIdTypeToSerial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up() {

		Schema::table('boxes', function (Blueprint $table) {
			$table->dropColumn('id');

		});
		Schema::table('boxes', function (Blueprint $table) {

			$table->increments('id');
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

	    Schema::table('boxes', function (Blueprint $table) {
		    $table->dropColumn('id');
	    });

        Schema::table('boxes', function (Blueprint $table) {
            $table->uuid('id');
        });
    }
}

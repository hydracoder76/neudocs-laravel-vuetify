<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSessionUserIdType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function(Blueprint $table) {
        	$table->string('user_id', 255)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

	    Schema::table('sessions', function(Blueprint $table) {
		    \DB::connection($this->getConnection());
		    \DB::beginTransaction();
		    \DB::statement('TRUNCATE ' . $table->getTable());
		    \DB::statement('ALTER TABLE ' . $table->getTable() . ' ALTER COLUMN user_id TYPE INT USING user_id::integer' );
		    \DB::commit();

	    });
    }
}

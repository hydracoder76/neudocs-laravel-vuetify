<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class MakePartIdNullableOnPartIndexes
 */
class MakePartIdNullableOnPartIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('part_indexes', function(Blueprint $table) {
		    $table->dropColumn('part_id');
	    });

        Schema::table('part_indexes', function(Blueprint $table) {
        	$table->uuid('part_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('part_indexes', function(Blueprint $table) {
		    $table->dropColumn('part_id');
	    });
        Schema::table('part_indexes', function(Blueprint $table) {
	        $table->uuid('part_id');
        });
    }
}

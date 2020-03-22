<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddLevelsToUserTable
 */
class AddLevelsToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
        	$table->dropColumn('level');

        });
        Schema::table('users', function(Blueprint $table) {
	        $table->enum('level', ['user', 'admin', 'client', 'it', 'neubus'])->default('client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
        	$table->dropColumn('level');

        });
	    Schema::table('users', function(Blueprint $table) {
		    $table->enum('level', ['user', 'admin'])->default('user');
	    });
    }
}

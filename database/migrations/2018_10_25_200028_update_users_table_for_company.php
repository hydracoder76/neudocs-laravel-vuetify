<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class UpdateUsersTableForCompany
 */
class UpdateUsersTableForCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
        	$table->uuid('company_id');
        	$table->enum('level', ['user', 'admin'])->default('user');
        	$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('users', function(Blueprint $table) {
		    $table->dropColumn('company_id');
		    $table->dropColumn('level');
	    });
    }
}

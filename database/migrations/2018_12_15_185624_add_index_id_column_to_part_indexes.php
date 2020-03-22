<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddIndexIdColumnToPartIndexes
 */
class AddIndexIdColumnToPartIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_indexes', function (Blueprint $table) {

        	// NSN-159 is this a sensible default?
            $table->unsignedInteger('index_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->dropColumn('index_id');
        });
    }
}

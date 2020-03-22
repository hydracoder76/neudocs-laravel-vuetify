<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddBoxIdToPartIndexes
 */
class AddBoxIdToPartIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->uuid('box_id')->nullable()->after('id');
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
            $table->dropColumn('box_id');
        });
    }
}

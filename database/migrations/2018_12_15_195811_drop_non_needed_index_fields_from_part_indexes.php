<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class DropNonNeededIndexFieldsFromPartIndexes
 */
class DropNonNeededIndexFieldsFromPartIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->dropColumn('part_index_label');
            $table->dropColumn('part_index_internal_name');

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
            $table->string('part_index_label')->default('');
            $table->string('part_index_internal_name')->default('');
        });
    }
}

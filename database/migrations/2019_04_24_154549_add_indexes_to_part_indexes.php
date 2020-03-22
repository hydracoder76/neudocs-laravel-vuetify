<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddIndexesToPartIndexes
 */
class AddIndexesToPartIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->index('index_type_id');
            $table->index('part_id');
            $table->index('box_id');

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
            $table->dropIndex('part_indexes_index_type_id_index');
            $table->dropIndex('part_indexes_part_id_index');
            $table->dropIndex('part_indexes_box_id_index');
        });
    }
}

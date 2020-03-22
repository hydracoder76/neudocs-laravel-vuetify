<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddIndexesToRequestParts
 */
class AddIndexesToRequestParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_parts', function (Blueprint $table) {
            $table->index('part_id_ref');
            $table->index('request_id_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_parts', function (Blueprint $table) {
            $table->dropIndex('request_parts_part_id_ref_index');
            $table->dropIndex('request_parts_request_id_ref_index');
        });
    }
}

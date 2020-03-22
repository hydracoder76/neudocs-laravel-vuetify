<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ChangeColumnNameInPivote
 */
class ChangeColumnNameInPivote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('index_index_type', function (Blueprint $table) {
            $table->renameColumn('index_id', 'part_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('index_index_type', function (Blueprint $table) {
            $table->renameColumn('part_id', 'index_id');
        });
    }
}

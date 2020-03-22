<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ChangePartTypeColumnsInPartPartType
 */
class ChangePartTypeColumnsInPartPartType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_part_type', function (Blueprint $table) {
            $table->renameColumn('part_id', 'index_id');
            $table->renameColumn('part_type_id', 'index_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_part_type', function (Blueprint $table) {
            $table->renameColumn('index_id', 'part_id');
            $table->renameColumn('index_type_id', 'index_id');
        });
    }
}

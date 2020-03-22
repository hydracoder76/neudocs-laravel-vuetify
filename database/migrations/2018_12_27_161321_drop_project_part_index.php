<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class DropProjectPartIndex
 */
class DropProjectPartIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::dropIfExists('project_part_index');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('project_part_index', function (Blueprint $table) {
            $table->unsignedBigInteger('part_index_id_ref');
            $table->unsignedBigInteger('project_schema_id_ref');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class DropIndexIndexType
 */
class DropIndexIndexType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('index_index_type');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('index_index_type', function(Blueprint $table) {
        	$table->uuid('part_id');
        	$table->unsignedInteger('index_type_id');
        });
    }
}

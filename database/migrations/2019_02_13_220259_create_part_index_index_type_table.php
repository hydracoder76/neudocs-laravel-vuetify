<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartIndexIndexTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_index_index_type', function (Blueprint $table) {
            $table->integer('part_indexes_id')->unsigned();
            $table->integer('index_types_id')->unsigned();

            //$table->foreign('part_indexes_id')->references('id')->on('part_indexes')->onDelete('cascade');
            //$table->foreign('index_types_id')->references('id')->on('index_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_index_index_type');
    }
}

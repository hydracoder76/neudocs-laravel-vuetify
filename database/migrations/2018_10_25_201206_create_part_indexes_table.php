<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePartIndexesTable
 */
class CreatePartIndexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_indexes', function (Blueprint $table) {
            $table->increments('id');
	        $table->uuid('part_id');
	        $table->uuid('company_id')->nullable();
	        $table->string('part_index_label');
	        $table->string('part_index_internal_name');
	        $table->string('part_index_value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_indexes');
    }
}

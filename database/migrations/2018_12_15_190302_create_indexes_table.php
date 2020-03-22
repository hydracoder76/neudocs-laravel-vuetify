<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateIndexesTable
 */
class CreateIndexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indexes', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger('index_type_id')->default(1);
	        $table->string('index_label')->default('');
	        $table->string('index_internal_name');
	        $table->text('index_description')->nullable();
	        $table->boolean('is_required')->default(false);
	        $table->boolean('is_required_double')->default(false);
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
        Schema::dropIfExists('indexes');
    }
}

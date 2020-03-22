<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateIndexTypeRelTable
 */
class CreateIndexTypeRelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_type_rel', function (Blueprint $table) {
            $table->unsignedInteger('index_type_id');
            $table->unsignedInteger('index_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('index_type_rel');
    }
}

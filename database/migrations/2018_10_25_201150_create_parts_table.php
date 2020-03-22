<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePartsTable
 */
class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('box_id');
            $table->string('part_name');
            $table->string('part_location_code', 255)->nullable();
            $table->text('part_description')->nullable();
            $table->uuid('project_id')->nullable();
            $table->uuid('created_by');
            $table->uuid('last_updated_by')->nullable();
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
        Schema::dropIfExists('parts');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateBoxesTable
 */
class CreateBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('box_name');
            $table->string('box_location_code', 255)->nullable();
	        $table->uuid('company_id');
	        $table->uuid('project_id')->nullable();
	        $table->boolean('has_pending_requests')->default(false);
	        $table->uuid('created_by');
	        $table->uuid('updated_by')->nullable();
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
        Schema::dropIfExists('boxes');
    }
}

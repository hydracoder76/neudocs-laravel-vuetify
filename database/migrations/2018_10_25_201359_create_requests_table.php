<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateRequestsTable
 */
class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('company_id');
            $table->uuid('created_by');
            $table->uuid('updated_by');
            $table->boolean('is_fulfilled');
            $table->boolean('is_in_process');
            $table->uuid('fulfilled_by')->nullable();
            $table->timestamp('fulfilled_on')->nullable();
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
        Schema::dropIfExists('requests');
    }
}

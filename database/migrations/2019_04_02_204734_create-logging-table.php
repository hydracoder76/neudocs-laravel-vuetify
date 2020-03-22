<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoggingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->uuid('project_id');
            $table->char('record_id')->nullable();
            $table->char('operation')->nullable();
            $table->unsignedInteger('level')->nullable();
            $table->char('message')->nullable();
            $table->text('details')->default('');
            $table->text('details_json')->default('{}');
            $table->char('route_name')->nullable();
            $table->char('route_method_name')->nullable();
            $table->boolean('db_action')->default(true);
            $table->char('ip_address')->default('');
            $table->uuid('company_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}

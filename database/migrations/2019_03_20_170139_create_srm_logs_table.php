<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSrmLogsTable
 */
class CreateSrmLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('srm_logs', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->string('record_id')->nullable();
            $table->enum('operation', ['create', 'retrieve', 'update', 'delete'])->nullable();
            $table->enum('level', ['debug', 'info', 'warn', 'error'])->default('info');
            $table->string('message')->default('');
            $table->text('details')->default('');
            $table->text('details_json')->default('{}');
            $table->string('route_name')->nullable();
            $table->string('route_method_name')->nullable();
            $table->boolean('db_action')->default(true);
            $table->ipAddress('ip_address')->nullable();
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
        Schema::dropIfExists('srm_logs');
    }
}

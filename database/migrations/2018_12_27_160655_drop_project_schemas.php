<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class DropProjectSchemas
 */
class DropProjectSchemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('project_schemas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('project_schemas', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('project_id');
            $table->uuid('company_id');
            $table->json('schema_definition');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}

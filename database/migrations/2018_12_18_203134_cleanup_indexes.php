<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanupIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('index_type_rel');
        Schema::dropIfExists('project_part_index');
        Schema::dropIfExists('project_schemas');
        Schema::table('indexes', function(Blueprint $table) {
            $table->unique(['index_internal_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('index_type_rel','index_type_id'))
        Schema::create('index_type_rel', function (Blueprint $table) {
            $table->unsignedInteger('index_type_id');
            $table->unsignedInteger('index_id');
        });
        if (!Schema::hasColumn('project_part_index','part_index_id_ref'))
        Schema::create('project_part_index', function (Blueprint $table) {
            $table->bigInteger('part_index_id_ref');
            $table->bigInteger('project_schema_id_ref');
        });
        if (!Schema::hasTable('project_schemas'))
        Schema::create('project_schemas', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('project_id');
            $table->uuid('company_id');
            $table->json('schema_definition');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('indexes', function(Blueprint $table) {
            $table->dropUnique(['index_internal_name']);
        });
    }
}

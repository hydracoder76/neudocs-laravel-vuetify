<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddProjectIdToIndexType
 */
class AddProjectIdToIndexType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('index_types', function (Blueprint $table) {
            $table->uuid('project_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('index_types', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}

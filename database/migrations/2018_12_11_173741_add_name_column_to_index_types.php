<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddNameColumnToIndexTypes
 */
class AddNameColumnToIndexTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('index_types', function (Blueprint $table) {
            $table->string('index_name')->after('index_type_name')->default('default name label');
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
            $table->dropColumn('index_name');
        });
    }
}

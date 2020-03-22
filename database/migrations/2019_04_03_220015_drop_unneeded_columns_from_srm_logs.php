<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class DropUnneededColumnsFromSrmLogs
 */
class DropUnneededColumnsFromSrmLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('srm_logs', function (Blueprint $table) {
            $table->dropColumn('route_name');
            $table->dropColumn('route_method_name');
            $table->dropColumn('db_action');
            $table->dropColumn('details_json');
            $table->string('responsible_table')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('srm_logs', function (Blueprint $table) {
            $table->string('route_name')->default('');
            $table->string('route_method_name')->default('');
            $table->boolean('db_action')->default(false);
            $table->string('details_json')->default('{}');
            $table->dropColumn('responsible_table');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBoxIdToInteger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->dropColumn('box_id');
        });
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('box_id');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->unsignedBigInteger('box_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->dropColumn('box_id');
        });
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->uuid('box_id');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('box_id');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->uuid('box_id');
        });
    }
}

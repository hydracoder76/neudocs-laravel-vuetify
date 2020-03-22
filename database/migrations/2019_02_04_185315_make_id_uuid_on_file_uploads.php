<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeIdUuidOnFileUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_uploads', function(Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('file_uploads', function(Blueprint $table) {
            $table->uuid('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_uploads', function(Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('file_uploads', function(Blueprint $table) {
            $table->increments('id');
        });
    }
}

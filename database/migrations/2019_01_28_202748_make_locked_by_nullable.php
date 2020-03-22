<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class MakeLockedByNullable
 */
class MakeLockedByNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_uploads', function(Blueprint $table) {
            $table->dropColumn('locked_by');

        });
        Schema::table('file_uploads', function(Blueprint $table) {
            $table->uuid('locked_by')->nullable();

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
            $table->dropColumn('locked_by');
        });
        Schema::table('file_uploads', function(Blueprint $table) {
            $table->uuid('locked_by');
        });

    }
}

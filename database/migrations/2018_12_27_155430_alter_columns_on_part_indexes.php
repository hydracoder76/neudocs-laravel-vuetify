<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AlterColumnsOnPartIndexes
 */
class AlterColumnsOnPartIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->renameColumn('index_id', 'index_type_id');

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
            $table->uuid('company_id')->after('part_id');
            $table->renameColumn('index_type_id', 'index_id');
        });
    }
}

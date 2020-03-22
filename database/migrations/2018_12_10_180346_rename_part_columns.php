<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class RenamePartColumns
 */
class RenamePartColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_types', function(Blueprint $table) {
        	$table->renameColumn('part_type_name', 'index_type_name');
        	$table->renameColumn('part_type_description', 'index_type_description');
        	$table->dropPrimary('part_types_pkey');
        	$table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('part_types', function(Blueprint $table) {
		    $table->renameColumn('index_type_name', 'part_type_name');
		    $table->renameColumn('index_type_description', 'part_type_description');
		    $table->dropPrimary('index_types_pkey');
		    $table->primary('id');
	    });
    }
}

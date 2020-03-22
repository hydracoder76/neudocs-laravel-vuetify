<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AlterIndexTypesForNewStructure
 */
class AlterIndexTypesForNewStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('index_types', function(Blueprint $table) {
        	$table->dropColumn('index_type_description');
        	// could easily be used on more than one project
        	$table->dropColumn('project_id');
        	$table->boolean('has_validation')->default(false);
        	$table->string('validation_regex')->nullable();

        	// provides more extensible features if needed
	        // needs a system in place to handle this, though
	        // which will need to be a separate story, probably
	        // for the future
        	$table->string('validation_rule_class_name')
		        ->nullable()
		        ->comment('fq class name to validate the field');
        	$table->uuid('created_by')->nullable();
        	$table->dropTimestamps();
        	$table->dropSoftDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('index_types', function(Blueprint $table) {
        	$table->text('index_type_description')->nullable();
        	$table->uuid('project_id')->nullable();
        	$table->dropColumn('has_validation');
        	$table->dropColumn('validation_regex');
        	$table->dropColumn('validation_rule_class_name');
        	$table->dropColumn('created_by');
        	$table->timestamps();
        	$table->softDeletes();
        });
    }
}

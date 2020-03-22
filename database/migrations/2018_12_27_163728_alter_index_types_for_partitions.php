<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AlterIndexTypesForPartitions
 */
class AlterIndexTypesForPartitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('index_types', function (Blueprint $table) {
        	$table->dropColumn('index_type_name');
        	$table->dropColumn('index_name');
	        $table->string('index_label')->default('');
	        $table->string('index_internal_name')->nullable()->unique();
	        $table->text('index_description')->nullable();
	        $table->boolean('is_required')->default(false);
	        $table->boolean('is_required_double')->default(false);
	        $table->timestamps();
	        $table->softDeletes();
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
            if (Schema::hasColumn('index_types','is_required'))
            $table->dropColumn('is_required');
            if (Schema::hasColumn('index_types','is_required_double'))
            $table->dropColumn('is_required_double');
            if (Schema::hasColumn('index_types','index_label'))
            $table->dropColumn('index_label');
            if (Schema::hasColumn('index_types','index_internal_name'))
            $table->dropColumn('index_internal_name');
            if (Schema::hasColumn('index_types','index_description'))
            $table->dropColumn('index_description');
            $table->dropTimestamps();
            $table->dropSoftDeletes();
            if (!Schema::hasColumn('index_types','index_type_name'))
	            $table->enum('index_type_name', ['date', 'date_range', 'email', 'multi_select', 'numeric',
		        'single_select', 'text', 'text_area'])->default('text');
            if (!Schema::hasColumn('index_types','index_name'))
            $table->string('index_name');
        });
    }
}

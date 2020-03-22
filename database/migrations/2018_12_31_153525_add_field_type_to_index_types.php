<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddFieldTypeToIndexTypes
 */
class AddFieldTypeToIndexTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up() {
		Schema::table('index_types', function (Blueprint $table) {
			$table->enum('index_type_name', [
				'date',
				'date_range',
				'email',
				'multi_select',
				'numeric',
				'single_select',
				'text',
				'text_area'
			])->default('text');
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasColumn('index_types','index_field_type'))
            Schema::table('index_types', function (Blueprint $table) {
                $table->dropColumn('index_field_type');
            });
    }
}

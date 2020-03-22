<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBoxTypeToTableBoxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasColumn('boxes','box_type'))
        Schema::table('boxes', function (Blueprint $table) {
            $table->enum('box_type', [
                'standard',
                'oversize',
                'media'
            ])->default('standard');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasColumn('boxes','box_type'))
            Schema::table('boxes', function (Blueprint $table) {
                $table->dropColumn('box_type');
            });
    }
}

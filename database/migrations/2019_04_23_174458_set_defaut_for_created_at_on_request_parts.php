<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class SetDefautForCreatedAtOnRequestParts
 */
class SetDefautForCreatedAtOnRequestParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE request_parts ALTER COLUMN created_at SET DEFAULT now()');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE request_parts ALTER COLUMN created_at DROP DEFAULT');
    }
}

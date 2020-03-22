<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangePartNameToInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parts', function (Blueprint $table) {
            DB::statement('ALTER TABLE parts ALTER COLUMN part_name TYPE integer USING (part_name::integer);');
            DB::statement('ALTER TABLE file_uploads ALTER COLUMN part_name TYPE integer USING (part_name::integer);');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parts', function (Blueprint $table) {
            DB::statement('ALTER TABLE parts ALTER COLUMN part_name TYPE VARCHAR(255) USING (part_name::VARCHAR(255));');
            DB::statement('ALTER TABLE file_uploads ALTER COLUMN part_name TYPE integer USING (part_name::integer);');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class SetDefaultCreateAtForAllTables
 */
class SetDefaultCreateAtForAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       \DB::statement('ALTER TABLE box_location_history ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE boxes ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE companies ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE file_types ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE file_uploads ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE index_types ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE part_indexes ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE parts ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE projects ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE requests ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE srm_logs ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE users ALTER COLUMN created_at SET DEFAULT now()');
       \DB::statement('ALTER TABLE verification_tokens ALTER COLUMN created_at SET DEFAULT now()');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE box_location_history ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE boxes ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE companies ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE file_types ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE file_uploads ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE index_types ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE part_indexes ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE parts ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE projects ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE requests ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE srm_logs ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE users ALTER COLUMN created_at SET DEFAULT null');
        \DB::statement('ALTER TABLE verification_tokens ALTER COLUMN created_at SET DEFAULT null');
    }
}

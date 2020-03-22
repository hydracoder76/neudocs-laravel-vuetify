<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddUuidAutoSupportWhereNeeded
 */
class AddUuidAutoSupportWhereNeeded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        \DB::statement('ALTER TABLE companies ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        \DB::statement('ALTER TABLE file_uploads ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        \DB::statement('ALTER TABLE parts ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        \DB::statement('ALTER TABLE projects ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        \DB::statement('ALTER TABLE requests ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        \DB::statement('ALTER TABLE srm_logs ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
        \DB::statement('ALTER TABLE users ALTER COLUMN id SET DEFAULT uuid_generate_v4()');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE companies ALTER COLUMN id DROP DEFAULT ');
        \DB::statement('ALTER TABLE file_uploads ALTER COLUMN id DROP DEFAULT ');
        \DB::statement('ALTER TABLE parts ALTER COLUMN id DROP DEFAULT ');
        \DB::statement('ALTER TABLE projects ALTER COLUMN id DROP DEFAULT');
        \DB::statement('ALTER TABLE requests ALTER COLUMN id DROP DEFAULT');
        \DB::statement('ALTER TABLE srm_logs ALTER COLUMN id DROP DEFAULT ');
        \DB::statement('ALTER TABLE users ALTER COLUMN id DROP DEFAULT');
        \DB::statement('DROP EXTENSION IF EXISTS "uuid-ossp"');
    }
}

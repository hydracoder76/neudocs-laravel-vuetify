<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestsNumberFulltextColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('request_name', 255)->nullable()->default('');
        });
        DB::statement('ALTER TABLE request_parts ADD COLUMN searchtext TSVECTOR');
        $triggers = $this->getTriggersForDriver();
        \DB::unprepared($triggers['create']);
        \DB::unprepared($triggers['update']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('request_name');
            DB::statement('ALTER TABLE request_parts DROP COLUMN searchtext');
            \DB::unprepared('drop trigger if exists tg_request_parts_insert_child on part_indexes');
            \DB::unprepared('drop trigger if exists tg_request_parts_update_child on part_indexes');
            \DB::unprepared('DROP FUNCTION IF EXISTS request_parts_insert_child()');
            \DB::unprepared('DROP FUNCTION IF EXISTS request_parts_update_child()');
        });
    }

    /**
     * @return array
     */
    protected function getTriggersForDriver() {
        if (\DB::getDriverName() == 'pgsql') {
            return ['create' => $this->getTriggerForPostgres(), 'update' => $this->getUpdateTriggerForPostgres()];
        }
        return [];
    }

    private function getTriggerForPostgres() {

        $triggerToCreate = <<<SQL
create or replace function request_parts_insert_child() returns trigger
  language plpgsql
as
$$
BEGIN
  If (TG_OP = 'INSERT' AND NEW.searchtext IS NOT NULL AND NEW.searchtext <> '') then
    NEW.searchtext = to_tsvector('english', cast(NEW.searchtext AS text));
  END if;
  RETURN NEW;
END;
$$;

    CREATE TRIGGER tg_request_parts_insert_child
	BEFORE INSERT ON request_parts
  FOR EACH ROW
  EXECUTE PROCEDURE request_parts_insert_child();
SQL;

        return $triggerToCreate;
    }

    private function getUpdateTriggerForPostgres(){
        $triggerToCreate = <<<SQL
create or replace function request_parts_update_child() returns trigger
  language plpgsql
as
$$
BEGIN
  If (TG_OP = 'UPDATE' AND NEW.searchtext <> OLD.searchtext) THEN
    NEW.searchtext = to_tsvector('english', cast(NEW.searchtext AS text));
  END IF;
  RETURN NEW;
END;
$$;

alter function request_parts_update_child() owner to postgres;
CREATE TRIGGER tg_request_parts_update_child 
	BEFORE UPDATE ON request_parts 
	FOR EACH ROW 
	EXECUTE PROCEDURE request_parts_update_child();
SQL;

        return $triggerToCreate;
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class FixPartsIndexesChildUpdateFunction
 */
class FixPartsIndexesChildUpdateFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::unprepared('drop trigger if exists tg_part_indexes_update_child on part_indexes');
        $triggers = $this->getTriggersForDriver();
        \DB::unprepared($triggers['update']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no need to replace this with the broken one, so this down can be empty
    }

    /**
     * @return array
     */
    protected function getTriggersForDriver() : array {
        if (\DB::getDriverName() === 'pgsql') {
            return ['update' => $this->getUpdateTriggerForPostgres()];
        }

        return [];
    }

    /**
     * @return string
     */
    private function getUpdateTriggerForPostgres() : string {
        $triggerToCreate = <<<SQL
create or replace function part_indexes_update_child() returns trigger
  language plpgsql
as
$$
DECLARE
  var_old_x INTEGER; var_new_x INTEGER; var_old_partIndexesTable TEXT; var_new_partIndexesTable TEXT; var_sql VARCHAR;
BEGIN
  If (TG_OP = 'UPDATE') THEN
    IF (NEW.box_id / 50000::float = NEW.box_id / 50000) THEN
      var_new_x := CAST(NEW.box_id AS INTEGER) / 50000;
    ELSE
      var_new_x := (NEW.box_id / 50000) + 1;
    END IF;
    IF (OLD.box_id / 50000::float = OLD.box_id / 50000) THEN
      var_old_x := CAST(OLD.box_id AS INTEGER) / 50000;
    ELSE
      var_old_x := (OLD.box_id / 50000) + 1;
    END IF;
    IF (var_old_x <> var_new_x) THEN
      var_new_partIndexesTable := 'part_indexes_' || var_new_x; var_old_partIndexesTable := 'part_indexes_' || var_old_x;
      var_sql := 'INSERT INTO ' || var_new_partIndexesTable || ' SELECT part_indexes.*' FROM part_indexes; EXECUTE var_sql USING NEW;
      var_sql := 'DELETE FROM ' || var_old_partIndexesTable || ' WHERE box_id=' || OLD.box_id || ' AND id = ' || OLD.id;
      EXECUTE var_sql; RETURN NULL;
    END IF;
    RETURN NEW;
  END IF;
  RETURN NEW;
END;
$$;

alter function part_indexes_update_child() owner to postgres;
CREATE TRIGGER tg_part_indexes_update_child 
	BEFORE UPDATE ON part_indexes 
	FOR EACH ROW 
	EXECUTE PROCEDURE part_indexes_update_child();
SQL;

        return $triggerToCreate;
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddExtraIndexesToPartitionFunctions
 */
class AddExtraIndexesToPartitionFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        \DB::unprepared('drop trigger if exists tg_part_indexes_insert_child on part_indexes');
        \DB::unprepared('drop trigger if exists tg_part_indexes_update_child on part_indexes');
        $triggers = $this->getTriggersForDriver();
        \DB::unprepared($triggers['create']);
        \DB::unprepared($triggers['update']);
        \DB::unprepared('create index part_indexes_lower_part_index_value_idx on part_indexes (lower(part_index_value::text))');
        Schema::table('parts', function(Blueprint $table) {
            $table->index('id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::unprepared('drop trigger if exists tg_part_indexes_insert_child on part_indexes');
        \DB::unprepared('drop trigger if exists tg_part_indexes_update_child on part_indexes');
        $triggers = $this->getTriggersForDriverOld();
        \DB::unprepared($triggers['create']);
        \DB::unprepared($triggers['update']);
        \DB::unprepared('drop index part_indexes_lower_part_index_value_idx');

        Schema::table('parts', function(Blueprint $table) {
            $table->dropIndex('parts_id_index');
        });
    }

    /**
     * @return array
     */
    protected function getTriggersForDriver() : array {
        if (\DB::getDriverName() === 'pgsql') {
            return ['create' => $this->getTriggerForPostgres(),
                'update' => $this->getUpdateTriggerForPostgres()];
        }

        return [];
    }

    /**
     * @return array
     */
    protected function getTriggersForDriverOld() : array {
        if (\DB::getDriverName() === 'pgsql') {
            return ['create' => $this->getTriggerForPostgresOld(),
                'update' => $this->getUpdateTriggerForPostgresOld()];
        }

        return [];
    }

    /**
     * @return string
     */
    private function getTriggerForPostgres() : string {

        $triggerToCreate = <<<SQL
create or replace function part_indexes_insert_child() returns trigger
  language plpgsql
as
$$
DECLARE
  var_x INTEGER; var_min INTEGER; var_max INTEGER; var_partIndexesTable TEXT; var_box_id INTEGER; var_sql VARCHAR; 
  var_constraint VARCHAR; var_exists BOOLEAN;
BEGIN
  If (TG_OP = 'INSERT') then
    IF (NEW.box_id / 50000::float = NEW.box_id / 50000) THEN
      var_x := CAST(NEW.box_id AS INTEGER) / 50000;
    ELSE
      var_x := (New.box_id / 50000) + 1;
    END IF;
    IF (NEW.box_id > 50000) THEN
      var_min := (var_x - 1) * 50000 + 1; var_max := var_x * 50000;
    ELSE
      var_min := var_x; var_max := var_x + 50000 - 1;
    END IF;
    var_partIndexesTable := 'part_indexes_' || var_x; var_constraint := 'part_indexes_' || var_x || '_box_id_check';
    var_sql :=
          'SELECT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = lower( ''' || var_partIndexesTable ||
          '''))';
    EXECUTE var_sql into var_exists;
    IF (NOT var_exists) THEN
      var_sql := 'CREATE TABLE ' || var_partIndexesTable || '( CONSTRAINT ' || var_constraint || ' CHECK ( box_id >= ' ||
                 var_min || ' AND box_id <= ' || var_max || ' )) INHERITS (part_indexes)';
      EXECUTE var_sql;
      var_sql := 'CREATE TRIGGER tg_' || var_partIndexesTable || '_update_child BEFORE UPDATE ON ' || var_partIndexesTable ||
                 ' FOR EACH ROW EXECUTE PROCEDURE part_indexes_update_child()';
      EXECUTE var_sql; var_sql := 'CREATE INDEX ' || var_partIndexesTable || '_box_idx ON ' || var_partIndexesTable || ' (box_id)';
      EXECUTE var_sql; var_sql := 'CREATE INDEX ' || var_partIndexesTable || '_lower_part_index_value_idx ON ' || var_partIndexesTable || ' (lower(part_index_value::text))';
      EXECUTE var_sql; var_sql := 'CREATE INDEX ' || var_partIndexesTable || '_part_id_index ON ' || var_partIndexesTable || ' (part_id)';
      EXECUTE var_sql; var_sql := 'CREATE INDEX ' || var_partIndexesTable || '_index_type_id_index ON ' || var_partIndexesTable || ' (index_type_id)';
      EXECUTE var_sql;
      var_sql := 'CREATE INDEX ' || var_partIndexesTable || '_box_id_idx ON ' || var_partIndexesTable || ' (box_id)';
      EXECUTE var_sql;
    END IF;
    var_sql := 'INSERT INTO ' || var_partIndexesTable || ' SELECT $1.*'; EXECUTE var_sql USING NEW;
    RETURN OLD;
  END if;
  RETURN NEW;
END;
$$;

    CREATE TRIGGER tg_part_indexes_insert_child
	BEFORE INSERT ON part_indexes
  FOR EACH ROW
  EXECUTE PROCEDURE part_indexes_insert_child();
SQL;

        return $triggerToCreate;
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
    IF (NEW.box_id / 50000::float = NEW.image_id / 50000) THEN
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

    /**
     * @return string
     */
    private function getTriggerForPostgresOld() : string {

        $triggerToCreate = <<<SQL
create or replace function part_indexes_insert_child() returns trigger
  language plpgsql
as
$$
DECLARE
  var_x INTEGER; var_min INTEGER; var_max INTEGER; var_partIndexesTable TEXT; var_box_id INTEGER; var_sql VARCHAR; 
  var_constraint VARCHAR; var_exists BOOLEAN;
BEGIN
  If (TG_OP = 'INSERT') then
    IF (NEW.box_id / 50000::float = NEW.box_id / 50000) THEN
      var_x := CAST(NEW.box_id AS INTEGER) / 50000;
    ELSE
      var_x := (New.box_id / 50000) + 1;
    END IF;
    IF (NEW.box_id > 50000) THEN
      var_min := (var_x - 1) * 50000 + 1; var_max := var_x * 50000;
    ELSE
      var_min := var_x; var_max := var_x + 50000 - 1;
    END IF;
    var_partIndexesTable := 'part_indexes_' || var_x; var_constraint := 'part_indexes_' || var_x || '_box_id_check';
    var_sql :=
          'SELECT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = lower( ''' || var_partIndexesTable ||
          '''))';
    EXECUTE var_sql into var_exists;
    IF (NOT var_exists) THEN
      var_sql := 'CREATE TABLE ' || var_partIndexesTable || '( CONSTRAINT ' || var_constraint || ' CHECK ( box_id >= ' ||
                 var_min || ' AND box_id <= ' || var_max || ' )) INHERITS (part_indexes)';
      EXECUTE var_sql;
      var_sql := 'CREATE TRIGGER tg_' || var_partIndexesTable || '_update_child BEFORE UPDATE ON ' || var_partIndexesTable ||
                 ' FOR EACH ROW EXECUTE PROCEDURE part_indexes_update_child()';
      EXECUTE var_sql; var_sql := 'CREATE INDEX ' || var_partIndexesTable || '_box_idx ON ' || var_partIndexesTable || ' (box_id)';
      EXECUTE var_sql;
      var_sql := 'CREATE INDEX ' || var_partIndexesTable || '_box_id_idx ON ' || var_partIndexesTable || ' (box_id)';
      EXECUTE var_sql;
    END IF;
    var_sql := 'INSERT INTO ' || var_partIndexesTable || ' SELECT $1.*'; EXECUTE var_sql USING NEW;
    RETURN OLD;
  END if;
  RETURN NEW;
END;
$$;

    CREATE TRIGGER tg_part_indexes_insert_child
	BEFORE INSERT ON part_indexes
  FOR EACH ROW
  EXECUTE PROCEDURE part_indexes_insert_child();
SQL;

        return $triggerToCreate;
    }

    /**
     * @return string
     */
    private function getUpdateTriggerForPostgresOld() : string {
        $triggerToCreate = <<<SQL
create or replace function part_indexes_update_child() returns trigger
  language plpgsql
as
$$
DECLARE
  var_old_x INTEGER; var_new_x INTEGER; var_old_partIndexesTable TEXT; var_new_partIndexesTable TEXT; var_sql VARCHAR;
BEGIN
  If (TG_OP = 'UPDATE') THEN
    IF (NEW.box_id / 50000::float = NEW.image_id / 50000) THEN
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

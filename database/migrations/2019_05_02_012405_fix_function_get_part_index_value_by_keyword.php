<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixFunctionGetPartIndexValueByKeyword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $functions = $this->getFunctionsForDriver();
        \DB::unprepared($functions['create']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::unprepared('drop function if exists get_part_index_value_by_keyword');
        \DB::unprepared('DROP FUNCTION IF EXISTS public.get_part_index_value_by_keyword(p_index_type_id integer, p_pattern text, p_limit integer)');
    }

    /**
     * @return array
     */
    protected function getFunctionsForDriver() {
        if (\DB::getDriverName() == 'pgsql') {
            return ['create' => $this->getFunctionForPostgres()];
        }

        return [];
    }

    /**
     * @return string
     */
    private function getFunctionForPostgres() {

        $functionToCreate = <<<SQL
CREATE OR REPLACE FUNCTION public.get_part_index_value_by_keyword(p_index_type_id integer, p_pattern text, p_limit integer)
 RETURNS TABLE(f2 character varying)
 LANGUAGE plpgsql
AS $$

DECLARE p_multiple_limit int;
BEGIN
        p_multiple_limit = p_limit * p_limit;
        create temp table temp_part_indexes as select part_index_value from part_indexes where is_deleted = 'f' and index_type_id = p_index_type_id and lower(part_index_value) like p_pattern limit p_multiple_limit;
        return query select part_index_value from temp_part_indexes group by part_index_value limit p_limit;
        drop table temp_part_indexes;
END;
$$    
SQL;

        return $functionToCreate;
    }
}
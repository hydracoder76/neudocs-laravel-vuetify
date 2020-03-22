<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddIsDeletedBooleanToAllTables
 */
class AddIsDeletedBooleanToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('box_location_history', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('boxes', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('file_types', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('index_types', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('project_indexes', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('request_parts', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('srm_logs', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('verification_tokens', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('box_location_history', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('boxes', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('file_types', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('file_uploads', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('index_types', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('part_indexes', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('request_parts', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('srm_logs', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
        Schema::table('verification_tokens', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
    }
}

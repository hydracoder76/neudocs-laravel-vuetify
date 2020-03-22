<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFileUploadsTable
 */
class CreateFileUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->increments('id');
            // just in case we allow some king of generic file upload
            // these are nullable
            $table->unsignedInteger('box_id')->nullable();
            $table->uuid('part_id')->nullable();

            $table->uuid('uploaded_by');
            $table->string('true_file_name');
            $table->string('hashed_file_name');
            $table->string('file_mime')->nullable();
            $table->string('current_fs_location');
            $table->boolean('is_locked');
            $table->uuid('locked_by');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_uploads');
    }
}

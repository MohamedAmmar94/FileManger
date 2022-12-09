<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbnailIdToFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->foreign('thumbnail_id', 'media_fk_6878888')->references('id')->on('media');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_6878889')->references('id')->on('users');
        });
    }


}

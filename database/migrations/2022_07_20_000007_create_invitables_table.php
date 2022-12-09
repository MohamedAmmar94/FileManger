<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitablesTable extends Migration
{
    public function up()
    {
        Schema::create('invitables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invitable_id')->nullable();
            $table->string('invitable_type');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}

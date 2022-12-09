<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToInvitablesTable extends Migration
{
    public function up()
    {
        Schema::table('invitables', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_7016421')->references('id')->on('users');
            $table->unsignedBigInteger('invited_by_id')->nullable();
            $table->foreign('invited_by_id', 'invited_by_fk_7016422')->references('id')->on('users');
        });
    }
}

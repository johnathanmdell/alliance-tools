<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->bigInteger('fleet_id');
            $table->bigInteger('character_id');
            $table->string('location');
            $table->string('ship');
            $table->dateTime('joined_at');
            $table->timestamps();

            $table->index('fleet_id');
            $table->index('character_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('member');
    }
}

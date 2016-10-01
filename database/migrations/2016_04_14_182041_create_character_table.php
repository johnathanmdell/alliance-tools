<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->integer('corporation_id')->nullable();
            $table->string('name');
            $table->boolean('primary')->default(false);
            $table->string('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('character');
    }
}

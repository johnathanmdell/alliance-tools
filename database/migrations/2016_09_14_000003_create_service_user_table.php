<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_user', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->integer('service_id');
            $table->integer('user_id');
            $table->string('auth_key')->nullable();
            $table->string('related_key')->nullable();
            $table->timestamps();

            $table->index('service_id');
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
        Schema::drop('service_user');
    }
}

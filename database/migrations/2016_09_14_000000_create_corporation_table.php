<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporation', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->integer('alliance_id')->nullable();
            $table->string('name');
            $table->timestamps();

            $table->primary('id');
            $table->index('alliance_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('corporation');
    }
}

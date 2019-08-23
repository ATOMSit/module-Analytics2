<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGooglesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics__googles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('website_id')
                ->unsigned()
                ->nullable();
            $table->foreign('website_id')
                ->references('id')
                ->on('websites');
            $table->string('view_id');
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
        Schema::dropIfExists('analytics__googles');
    }
}

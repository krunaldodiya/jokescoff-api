<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('parent_id');
            $table->string('name');
            $table->string('cover');
            $table->tinyInteger('sort');
            $table->timestamps();
            $table->tinyInteger('status')->default(1); // 1 => activated 0 => de-activated 2 => invited
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }
}

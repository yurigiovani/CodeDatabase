<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeCategoryTable
{

    public function up()
    {
        Schema::create('codedatabase_categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('codedatabase_categories');
    }
}
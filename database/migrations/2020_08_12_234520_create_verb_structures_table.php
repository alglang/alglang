<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerbStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verb_structures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('mode_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('primary_object_id')->nullable();
            $table->unsignedInteger('secondary_object_id')->nullable();
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
        Schema::dropIfExists('verb_structures');
    }
}

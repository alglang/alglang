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
            $table->string('class_abv');
            $table->string('order_name');
            $table->unsignedInteger('mode_name');
            $table->unsignedInteger('subject_name');
            $table->unsignedInteger('primary_object_name')->nullable();
            $table->unsignedInteger('secondary_object_name')->nullable();
            $table->boolean('is_negative')->default(false);
            $table->boolean('is_diminutive')->default(false);
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

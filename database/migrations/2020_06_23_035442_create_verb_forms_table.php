<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerbFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verb_forms', function (Blueprint $table) {
            $table->id();
            $table->string('shape');

            $table->unsignedInteger('language_id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('mode_id');
            $table->unsignedInteger('subject_id');

            $table->text('historical_notes')->nullable();
            $table->text('allomorphy_notes')->nullable();
            $table->text('usage_notes')->nullable();
            $table->text('private_notes')->nullable();

            $table->string('slug');
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
        Schema::dropIfExists('verb_forms');
    }
}

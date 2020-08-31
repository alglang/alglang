<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examples', function (Blueprint $table) {
            $table->id();
            $table->string('shape');
            $table->string('phonemic_shape')->nullable();
            $table->unsignedInteger('stem_id')->nullable();
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('slug');
            $table->string('translation');
            $table->text('notes')->nullable();
            $table->text('private_notes')->nullable();
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
        Schema::dropIfExists('examples');
    }
}

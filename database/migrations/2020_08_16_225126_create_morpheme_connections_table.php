<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMorphemeConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morpheme_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('morpheme_id')->nullable();
            $table->integer('position');
            $table->string('shape')->nullable();
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
        Schema::dropIfExists('morpheme_connections');
    }
}

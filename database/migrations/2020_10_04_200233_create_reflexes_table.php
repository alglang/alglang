<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReflexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reflexes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('phoneme_id');
            $table->unsignedInteger('reflex_id');
            $table->string('environment')->nullable();
            $table->text('public_notes')->nullable();
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
        Schema::dropIfExists('reflexes');
    }
}

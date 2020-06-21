<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMorphemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morphemes', function (Blueprint $table) {
            $table->id();
            $table->string('shape');
            $table->string('slug');
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('slot_id');
            $table->text('historical_notes')->nullable();
            $table->text('allomorphy_notes')->nullable();
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
        Schema::dropIfExists('morphemes');
    }
}

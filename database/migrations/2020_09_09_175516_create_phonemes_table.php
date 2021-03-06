<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phonemes', function (Blueprint $table) {
            $table->id();
            $table->string('shape')->nullable();
            $table->string('ipa')->nullable();
            $table->string('slug');
            $table->string('language_code');
            $table->string('featureable_type')->nullable();
            $table->unsignedInteger('featureable_id')->nullable();
            $table->boolean('is_marginal')->default(false);
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
        Schema::dropIfExists('phonemes');
    }
}

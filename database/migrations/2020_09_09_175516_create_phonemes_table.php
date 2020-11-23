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
            $table->string('slug');
            $table->string('language_code');
            $table->morphs('featureable');
            $table->boolean('is_marginal')->default(false);
            $table->boolean('is_archiphoneme')->default(false);
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

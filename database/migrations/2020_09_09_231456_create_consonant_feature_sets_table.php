<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsonantFeatureSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consonant_feature_sets', function (Blueprint $table) {
            $table->id();
            $table->string('shape');
            $table->string('place_name')->nullable();
            $table->string('manner_name')->nullable();
            $table->integer('order_key')->default(-1);
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
        Schema::dropIfExists('consonant_feature_sets');
    }
}

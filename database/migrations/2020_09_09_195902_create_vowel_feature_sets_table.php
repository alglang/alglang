<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVowelFeatureSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vowel_feature_sets', function (Blueprint $table) {
            $table->id();
            $table->string('height_name')->nullable();
            $table->string('backness_name')->nullable();
            $table->string('length_name')->nullable();
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
        Schema::dropIfExists('vowel_feature_sets');
    }
}

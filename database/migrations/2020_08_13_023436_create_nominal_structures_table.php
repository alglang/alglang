<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominalStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominal_structures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pronominal_feature_name')->nullable();
            $table->unsignedInteger('nominal_feature_name')->nullable();
            $table->unsignedInteger('paradigm_id');
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
        Schema::dropIfExists('nominal_structures');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app('env') === 'testing') {
            Schema::create('parented', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->string('parent_name')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parented');
    }
}

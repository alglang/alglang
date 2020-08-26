<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->string('name');
            $table->string('code');
            $table->string('iso')->nullable();
            $table->text('alternate_names')->nullable();
            $table->boolean('reconstructed')->default(false);
            $table->string('position')->nullable();
            $table->text('notes')->nullable();
            $table->integer('order_key')->default(-1);
            $table->string('group_name');
            $table->string('parent_code')->nullable();
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
        Schema::dropIfExists('languages');
    }
}

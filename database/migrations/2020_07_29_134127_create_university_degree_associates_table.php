<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityDegreeAssociatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_degree_associates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uni_id')->unsigned()->nullable();
            $table->foreign('uni_id')->references('id')->on('university_models')->onDelete('cascade');
            $table->bigInteger('degree_id')->unsigned()->nullable();
            $table->foreign('degree_id')->references('id')->on('degree_models')->onDelete('cascade');

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
        Schema::dropIfExists('university_degree_associates');
    }
}

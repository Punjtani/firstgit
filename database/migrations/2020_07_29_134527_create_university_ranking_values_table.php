<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityRankingValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_ranking_values', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('uni_id')->unsigned()->nullable();
            $table->foreign('uni_id')->references('id')->on('university_models')->onDelete('cascade');
            $table->bigInteger('ranking_type_id')->unsigned()->nullable();
            $table->foreign('ranking_type_id')->references('id')->on('university_ranking_types')->onDelete('cascade');

            $table->string('value')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('university_ranking_values');
    }
}

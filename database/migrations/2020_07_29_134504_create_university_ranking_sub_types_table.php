<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityRankingSubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_ranking_sub_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rank_type_id')->unsigned()->nullable();
            $table->foreign('rank_type_id')->references('id')->on('university_ranking_types')->onDelete('cascade');

            $table->string('title')->nullable();
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
        Schema::dropIfExists('university_ranking_sub_types');
    }
}

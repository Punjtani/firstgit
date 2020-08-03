<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_models', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('email')->unique();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('location');
            $table->string('logo');
            $table->string('banner');
            $table->string('founded');
            $table->string('students');
            $table->string('alumni');
            $table->string('school');
            $table->string('majors');
            $table->string('academics');
            $table->string('uni_type_id');
            $table->string('campus_count');
            $table->string('academic_year_type_id');
            $table->string('language_id');
            $table->string('distance_learning');
            $table->string('online_courses');
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
        Schema::dropIfExists('university_models');
    }
}

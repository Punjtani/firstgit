<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityAdmissionRequirmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_admission_requirments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uni_id')->unsigned()->nullable();
            $table->foreign('uni_id')->references('id')->on('university_models')->onDelete('cascade');

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
        Schema::dropIfExists('university_admission_requirments');
    }
}

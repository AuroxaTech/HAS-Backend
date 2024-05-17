<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('service_name',255)->nullable();
            $table->string('description',255)->nullable();
            $table->integer('category_id')->nullable();
            $table->string('pricing',255)->nullable();
            $table->integer('duration_id')->nullable();
            $table->string('start_time',255)->nullable();
            $table->string('end_time',255)->nullable();
            $table->string('location',255)->nullable();
            $table->string('lat',255)->nullable();
            $table->string('long',255)->nullable();
            $table->string('media',255)->nullable();
            $table->string('additional_information',255)->nullable();
            $table->string('country',255)->nullable();
            $table->string('city',255)->nullable();
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
        Schema::dropIfExists('services');
    }
};

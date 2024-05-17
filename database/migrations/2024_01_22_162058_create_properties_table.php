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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('images',255)->nullable();
            $table->string('city',255)->nullable();
            $table->string('amount',255)->nullable();
            $table->string('address',255)->nullable();
            $table->string('lat',255)->nullable();
            $table->string('long',255)->nullable();
            $table->string('area_range',255)->nullable();
            $table->string('bedroom',255)->nullable();
            $table->string('bathroom',255)->nullable();
            $table->string('description',255)->nullable();
            $table->string('electricity_bill',255)->nullable();
            $table->integer('property_type')->nullable();
            $table->integer('property_sub_type')->nullable();
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
        Schema::dropIfExists('properties');
    }
};

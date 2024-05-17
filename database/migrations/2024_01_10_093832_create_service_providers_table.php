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
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('services',255)->nullable();
            $table->string('year_experience',255)->nullable();
            $table->string('availability_start_time',255)->nullable();
            $table->string('availability_end_time',255)->nullable();
            $table->string('cnic_front',255)->nullable();
            $table->string('cnic_end',255)->nullable();
            $table->string('certification',255)->nullable();
            $table->string('file',255)->nullable();
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
        Schema::dropIfExists('service_providers');
    }
};

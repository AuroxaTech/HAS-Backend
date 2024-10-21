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
        Schema::create('service_provider_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('serviceprovider_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->string('address',255)->nullable();
            $table->string('lat',255)->nullable();
            $table->string('long',255)->nullable();
            $table->integer('property_type')->nullable();
            $table->string('price',255)->nullable();
            $table->string('postal_code',255)->nullable();
            $table->string('is_applied')->default('false');
            $table->string('date',255)->nullable();
            $table->string('time',255)->nullable();
            $table->text('description')->nullable();
            $table->text('additional_info')->nullable();
            $table->integer('approved')->default('0')->nullable();
            $table->integer('decline')->default('0')->nullable();
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
        Schema::dropIfExists('service_provider_requests');
    }
};

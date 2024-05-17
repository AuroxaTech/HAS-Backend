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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('last_status',255)->nullable();
            $table->string('last_tenancy',255)->nullable();
            $table->string('last_landlord_name',255)->nullable();
            $table->string('last_landlord_contact',255)->nullable();
            $table->string('occupation',255)->nullable();
            $table->string('leased_duration',255)->nullable();
            $table->string('no_of_occupants',255)->nullable();
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
        Schema::dropIfExists('tenants');
    }
};

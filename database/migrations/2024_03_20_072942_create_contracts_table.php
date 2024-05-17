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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('property_id')->nullable();
            $table->integer('landlord_id')->nullable();
            $table->string('landlordName',250)->nullable();
            $table->string('landlordAddress',250)->nullable();
            $table->string('landlordPhone',250)->nullable();
            $table->string('tenantName',250)->nullable();
            $table->string('tenantAddress',250)->nullable();
            $table->string('tenantPhone',250)->nullable();
            $table->string('tenantEmail',250)->nullable();
            $table->string('occupants',250)->nullable();
            $table->string('premisesAddress',250)->nullable();
            $table->string('propertyType',250)->nullable();
            $table->string('leaseStartDate',250)->nullable();
            $table->string('leaseEndDate',250)->nullable();
            $table->string('leaseType',250)->nullable();
            $table->string('rentAmount',250)->nullable();
            $table->string('rentDueDate',250)->nullable();
            $table->string('rentPaymentMethod',250)->nullable();
            $table->string('securityDepositAmount',250)->nullable();
            $table->string('includedUtilities',250)->nullable();
            $table->string('tenantResponsibilities',250)->nullable();
            $table->string('emergencyContactName',250)->nullable();
            $table->string('emergencyContactPhone',250)->nullable();
            $table->string('emergencyContactAddress',250)->nullable();
            $table->string('buildingSuperintendentName',250)->nullable();
            $table->string('buildingSuperintendentAddress',250)->nullable();
            $table->string('buildingSuperintendentPhone',250)->nullable();
            $table->string('rentIncreaseNoticePeriod',250)->nullable();
            $table->string('noticePeriodForTermination',250)->nullable();
            $table->string('latePaymentFee',250)->nullable();
            $table->string('rentalIncentives',250)->nullable();
            $table->string('additionalTerms',250)->nullable();
            $table->integer('status')->default('0')->nullable();
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
        Schema::dropIfExists('contracts');
    }
};

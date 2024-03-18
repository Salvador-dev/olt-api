<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dummy', function (Blueprint $table) {
            $table->bigInteger('id', true)->comment('ONU');
            $table->string('unique_external_id')->comment('Unique External ID SN');
            $table->string('sn')->comment('SERIAL');
            $table->bigInteger('olt_id')->index('olt_id')->comment('OLT ID');
            $table->bigInteger('onu_type_id')->index('onu_type_id');
            $table->string('status')->nullable()->comment('Online | Ofline');
            $table->bigInteger('zone_id')->index('zone_id');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dummy');
    }
};

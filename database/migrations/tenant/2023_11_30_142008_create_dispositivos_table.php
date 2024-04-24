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
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hardware_version_id'); 
            $table->foreign('hardware_version_id')->references('id')->on('hardware_versions');
            $table->unsignedBigInteger('client_id')->nullable(); 
            // $table->foreign('client_id')->references('id')->on('client')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivos');
    }
};

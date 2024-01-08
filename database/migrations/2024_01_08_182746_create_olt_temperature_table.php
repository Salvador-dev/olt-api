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
        Schema::create('olt_temperature', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('olt_id');
            $table->integer('uptime');
            $table->float('env_temp');
            $table->timestamps();

            // Definir la clave foránea
            $table->foreign('olt_id')->references('id')->on('olts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olt_temperature');
    }
};

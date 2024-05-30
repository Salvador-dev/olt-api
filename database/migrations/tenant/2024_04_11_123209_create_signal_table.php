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
        Schema::create('signal', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('signal_id')->unique();
            $table->string('description');
            $table->float('max_frequency')->nullable(); // TODO quitar nullable
            $table->string('value_unit', 20)->default('dBm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signal');
    }
};

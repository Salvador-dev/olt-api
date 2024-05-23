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
        Schema::create('reports', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('action', 1400);
            $table->bigInteger('onu_id')->index('onu_id');
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('onu_id', 'reports_ibfk_2')->references('id')->on('onus')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id', 'reports_ibfk_3')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

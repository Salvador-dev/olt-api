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
        Schema::create('diagnostics', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('signal_value', 50)->nullable();
            $table->string('distance', 50)->nullable();
            $table->bigInteger('onu_id')->index('onu_id')->unique();
            $table->integer('status_id')->default(5);
            $table->integer('signal_id')->default(4);
            $table->timestamps();

            $table->foreign('onu_id', 'diagnostics_ibfk_1')->references('id')->on('onus')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['status_id'], 'diagnostics_ibfk_2')->references(['status_id'])->on('status');
            $table->foreign(['signal_id'], 'diagnostics_ibfk_3')->references(['signal_id'])->on('signal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostics');
    }
};

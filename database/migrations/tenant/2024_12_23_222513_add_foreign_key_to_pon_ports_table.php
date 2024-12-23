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
        Schema::table('pon_ports', function (Blueprint $table) {
            $table->foreign(['administrative_status_id'], 'pon_ports_ibfk_3')->references(['status_id'])->on('administrative_status')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pon_ports', function (Blueprint $table) {
            //
        });
    }
};

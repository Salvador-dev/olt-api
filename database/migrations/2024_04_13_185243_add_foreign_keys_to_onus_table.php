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
        Schema::table('onus', function (Blueprint $table) {

            $table->foreign(['status_id'], 'onus_ibfk_10')->references(['id'])->on('status');
            $table->foreign(['signal_id'], 'onus_ibfk_8')->references(['id'])->on('signal');
            $table->foreign(['speed_profile_id'], 'onus_ibfk_9')->references(['id'])->on('speed_profiles');
            $table->foreign(['administrative_status_id'], 'onus_ibfk_11')->references(['id'])->on('administrative_status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('onus', function (Blueprint $table) {
            $table->dropForeign('onus_ibfk_10');
            $table->dropForeign('onus_ibfk_8');
            $table->dropForeign('onus_ibfk_9');
            $table->dropForeign('onus_ibfk_11');

        });
    }
};

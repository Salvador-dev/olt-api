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

            $table->foreign(['speed_profile_id'], 'onus_ibfk_9')->references(['id'])->on('speed_profiles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['administrative_status_id'], 'onus_ibfk_11')->references(['status_id'])->on('administrative_status')->onUpdate('cascade')->onDelete('cascade');
// CORRER MIGRACIONES
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

            $table->dropForeign('onus_ibfk_9');
            $table->dropForeign('onus_ibfk_11');

        });
    }
};

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
        Schema::table('service_ports', function (Blueprint $table) {
            $table->foreign(['up_speed_id'], 'service_ports_ibfk_3')->references(['id'])->on('speed_profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['download_speed_id'], 'service_ports_ibfk_4')->references(['id'])->on('speed_profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['onu_id'], 'service_ports_ibfk_5')->references(['id'])->on('onus')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_ports', function (Blueprint $table) {
            $table->dropForeign('service_ports_ibfk_3');
            $table->dropForeign('service_ports_ibfk_4');
            $table->dropForeign('service_ports_ibfk_5');
        });
    }
};
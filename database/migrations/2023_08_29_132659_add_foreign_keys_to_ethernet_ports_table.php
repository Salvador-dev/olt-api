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
        Schema::table('ethernet_ports', function (Blueprint $table) {
            $table->foreign(['onu_id'], 'ethernet_ports_ibfk_1')->references(['id'])->on('onus')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ethernet_ports', function (Blueprint $table) {
            $table->dropForeign('ethernet_ports_ibfk_1');
        });
    }
};

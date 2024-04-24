<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pon_ports', function (Blueprint $table) {
            $table->unsignedInteger('onus_active')->default(0)->after('onus');
            // Puedes ajustar el tipo de dato y las opciones según tus necesidades
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pon_ports', function (Blueprint $table) {
            $table->dropColumn('onus_active');
        });
    }
};

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
            $table->string('port')->after('id')->nullable(); 
        });
    }

    public function down()
    {
        Schema::table('pon_ports', function (Blueprint $table) {
            $table->dropColumn('port');
        });
    }
};

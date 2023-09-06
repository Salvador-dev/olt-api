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
        Schema::table('onu_types', function (Blueprint $table) {
            $table->foreign(['capability_id'], 'onu_types_ibfk_1')->references(['id'])->on('capabilitys');
            $table->foreign(['pon_type_id'], 'onu_types_ibfk_2')->references(['id'])->on('pon_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('onu_types', function (Blueprint $table) {
            $table->dropForeign('onu_types_ibfk_1');
            $table->dropForeign('onu_types_ibfk_2');
        });
    }
};

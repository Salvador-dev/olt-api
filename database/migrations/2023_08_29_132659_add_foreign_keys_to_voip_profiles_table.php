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
        Schema::table('voip_profiles', function (Blueprint $table) {
            $table->foreign(['olt_id'], 'voip_profiles_ibfk_1')->references(['id'])->on('olts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['pon_type_id'], 'voip_profiles_ibfk_2')->references(['id'])->on('pon_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voip_profiles', function (Blueprint $table) {
            $table->dropForeign('voip_profiles_ibfk_1');
            $table->dropForeign('voip_profiles_ibfk_2');
        });
    }
};

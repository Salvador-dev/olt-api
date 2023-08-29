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
        Schema::table('remote_acls', function (Blueprint $table) {
            $table->foreign(['olt_id'], 'remote_acls_ibfk_1')->references(['id'])->on('olts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remote_acls', function (Blueprint $table) {
            $table->dropForeign('remote_acls_ibfk_1');
        });
    }
};

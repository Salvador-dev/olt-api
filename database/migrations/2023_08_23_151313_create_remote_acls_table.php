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
        Schema::create('remote_acls', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('olt_id')->index('olt_id');
            $table->string('acces_list_1')->nullable();
            $table->string('acces_list_2')->nullable();
            $table->string('acces_list_3')->nullable();
            $table->string('acces_list_4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remote_acls');
    }
};

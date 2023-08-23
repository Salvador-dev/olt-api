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
        Schema::create('ethernet_ports', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('port');
            $table->boolean('admin_state');
            $table->string('mode')->comment('["lan","access","hybrid","trunk","transparent"]');
            $table->string('dhcp')->nullable();
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
        Schema::dropIfExists('ethernet_ports');
    }
};

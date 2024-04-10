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
            $table->bigInteger('onu_id')->nullable()->index('ethernet_ports_ibfk_1_idx');
            $table->string('port')->nullable();
            $table->string('admin_state')->nullable();
            $table->string('mode')->nullable()->comment('["lan","access","hybrid","trunk","transparent"]');
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

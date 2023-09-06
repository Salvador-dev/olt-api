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
        Schema::create('onus_management_ips', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('start_ip', 15)->nullable();
            $table->string('end_ip', 15)->nullable()->comment('Nullable hasta llenar base de datos');
            $table->string('subnet_mask')->nullable()->comment('Nullable hasta llenar base de datos');
            $table->string('default_gateway')->nullable()->comment('Nullable hasta llenar base de datos');
            $table->string('dns1')->nullable()->comment('Nullable hasta llenar base de datos');
            $table->string('dns2')->nullable()->comment('Nullable hasta llenar base de datos');
            $table->bigInteger('olt_id')->index('olt_id');
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
        Schema::dropIfExists('onus_management_ips');
    }
};

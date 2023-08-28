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
        Schema::create('service_ports', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('onu_id')->nullable();
            $table->bigInteger('vlan_id')->nullable()->index('vlan_id')->comment('{
                 "service_port": "111 example",
                    "vlan": "100 example",
                    "cvlan": "example",
                    "svlan": "2 example",
                    "tag_transform_mode": "translate example",
                    "upload_speed": "FTTH_300 example",
                    "download_speed": "FTTH_300 example"
                }');
            $table->bigInteger('svlan_id')->nullable()->index('svlan_id');
            $table->string('tag_mode')->comment('Campo por definir');
            $table->bigInteger('download_speed_id')->index('download_speed_id');
            $table->bigInteger('up_speed_id')->index('up_speed_id');
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
        Schema::dropIfExists('service_ports');
    }
};

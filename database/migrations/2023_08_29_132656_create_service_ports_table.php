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
            $table->bigInteger('onu_id')->nullable()->index('service_ports_ibfk_5_idx');
            $table->string('vlan_id')->nullable()->index('vlan_id')->comment('{\\n                 "service_port": "111 example",\\n                    "vlan": "100 example",\\n                    "cvlan": "example",\\n                    "svlan": "2 example",\\n                    "tag_transform_mode": "translate example",\\n                    "upload_speed": "FTTH_300 example",\\n                    "download_speed": "FTTH_300 example"\\n                }');
            $table->string('svlan_id')->nullable()->index('svlan_id');
            $table->string('cvlan_id', 45)->nullable();
            $table->string('tag_mode')->nullable()->comment('Campo por definir');
            $table->string('service_port', 45)->nullable();
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

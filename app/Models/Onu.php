<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onu extends Model
{
    use HasFactory;
    protected $table = 'onus';
    protected $fillable = [
        'unique_external_id',
        'pon_type',
        'sn',
        'olt_id',
        'board',
        'port',
        'onu_type_id',
        'zone_id',
        'name',
        'address',
        'odb_name',
        'mode',
        'wan_mode',
        'ip_address',
        'subnet_mask',
        'default_gateway',
        'dns1',
        'dns2',
        'username',
        'password',
        'catv',
        'administrative_status',
        'authorization_date',
        'status',
        'signal',
        'signal_1310',
        'latitude',
        'longitude',
        'services_port_id',
        'ethernet_port_id',
        'wifi_port_id',
    ];
}

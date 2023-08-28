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
        'pon_type_id',
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
        'wifi_port_id',
    ];

    public function service_ports()
    {
        return $this->hasMany(ServicePort::class);
    }

    public function ethernet_ports()
    {
        return $this->hasMany(EthernetPort::class);
    }

    public function olt()
    {
        return $this->belongsTo(Olt::class);
    }
}

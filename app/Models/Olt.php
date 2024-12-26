<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Olt extends Model
{
    use HasFactory;
    protected $table = 'olts';
    protected $fillable = [
        'name',
        'olt_hardware_version_id',
        'olt_software_version_id',
        'ip',
        'telnet_port',
        'telnet_username',
        'telnet_password',
        'snmp_read_only',
        'snmp_read_write',
        'snmp_udp_port',
        'ipvt_module',
        'pon_type_id',
        'smart_olt_id'
        
    ];

    public function onus()
    {
        return $this->hasMany(Onu::class);
    }

    public function uplinks()
    {
        return $this->hasMany(Uplink::class);
    }

    public function vlans()
    {
        return $this->hasMany(Vlan::class);
    }

    public function temperatures()
    {
        return $this->hasMany(OltTemperature::class);
    }

    public function olt_cards()
    {
        return $this->hasMany(OltCard::class);
    }

    public function pon_ports()
    {
        return $this->hasMany(PonPort::class);
    }

    public function hardwareVersion()
    {
        return $this->belongsTo(HardwareVersion::class, 'olt_hardware_version_id');
    }

    public function softwareVersion()
    {
        return $this->belongsTo(SoftwareVersion::class, 'olt_software_version_id');
    }

}

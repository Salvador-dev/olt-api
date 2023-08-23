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
    ];
}

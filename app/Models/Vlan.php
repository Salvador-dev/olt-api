<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vlan extends Model
{
    use HasFactory;
    protected $table = 'vlans';
    protected $fillable = [
        'vlan_id',
        'description',
        'multicast_vlan',
        'management_voip',
        'dhcp_snooping',
        'lan_to_lan',
        'pon_ports',
        'olt_id',
    ];
}

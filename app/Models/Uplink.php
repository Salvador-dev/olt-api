<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uplink extends Model
{
    use HasFactory;
    protected $table = 'uplinks';
    protected $fillable = [
        'olt_id',
        'name',
        'uplink_port',
        'description',
        'type',
        'admin_state',
        'status',
        'negotiation',
        'mtu',
        'wavel',
        'temp',
        'pivd_untag',
        'mode_vlan',
    ];
}

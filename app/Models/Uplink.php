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
        'description',
        'type',
        'administrative_status_id',
        'status',
        'negotiation',
        'mtu',
        'wavel',
        'temp',
        'pivd',
        'mode',
        'vlan_tag',
    ];
}

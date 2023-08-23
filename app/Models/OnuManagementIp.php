<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnuManagementIp extends Model
{
    use HasFactory;
    protected $table = 'onus_management_ips';
    protected $fillable = [
        'start_ip',
        'end_ip',
        'subnet_mask',
        'default_gateway',
        'dns1',
        'dns2',
        'olt_id',
    ];
}

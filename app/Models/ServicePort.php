<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePort extends Model
{
    use HasFactory;
    protected $table = 'service_ports';
    protected $fillable = [
        'vlan_id',
        'svlan_id',
        'tag_mode',
        'download_speed_id',
        'up_speed_id',
    ];
}

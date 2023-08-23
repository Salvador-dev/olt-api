<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoipProfile extends Model
{
    use HasFactory;
    protected $table = 'voip_profiles';
    protected $fillable = [
        'olt_id',
        'pon_type_id',
        'profile_name',
        'server',
        'server_port',
        'proxy_server',
        'outbound_server_port',
        'user_agent_domain',
        'primary_dns',
        'secondary_dns',
        'udp_port',
    ];
}

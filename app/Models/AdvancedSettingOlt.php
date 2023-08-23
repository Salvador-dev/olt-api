<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancedSettingOlt extends Model
{
    use HasFactory;
    protected $table = 'advanced_settings_olts';
    protected $fillable = [
        'admin_onu_unconfigured',
        'dhcp_option82',
        'option82_field',
        'pppoe',
        'onu_ip_source',
        'mac_learn',
        'vport_onu',
        'mac_allow_list',
        'mac_drop_list',
        'use_acl',
        'ports_acl',
        'onu_signal_warning',
        'onu_signal_critical',
        'separate_voip_mgmt',
        'temperature_warning',
        'temperature_critical',
        'use_cvlan',
        'use_svlan',
        'tag_transform_mode',
        'use_tls_vlan',
        'olt_id',
    ];
}

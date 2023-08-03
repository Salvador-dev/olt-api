<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onu extends Model
{
    use HasFactory;
    protected $table = 'onus';
    // columnas de la tabla
    protected $fillable  = [
        'autoincrement',
        'onu_external',
        'pon_type',
        'sn',
        'onu_type',
        'name',
        'olt_id',
        'board',
        'port',
        'allocated_onu',
        'zone_id',
        'address',
        'lat',
        'lng',
        'odb_id',
        'mode',
        'wam_mode',
        'ip_address',
        'subnet_mask',
        'default_gateway',
        'dns1',
        'dns2',
        'username',
        'password',
        'catv',
        'administrative_status',
        'auth_date',
        'status',
        'signal',
        'signal_1310',
        'signal_1490',
        'distance',
        'service_port',
        'service_port_vlan',
        'service_port_cvlan',
        'service_port_svlan',
        'service_port_tag_transform_mode',
        'speed_up_id',
        'speed_download_id',
    ];

    public function olt()
    {
        return $this->belongsTo(Olt::class, 'olt_id');
    }

    public function speedProfileUp(){
        return $this->belongsTo(SpeedProfile::class, 'speed_up_id');
    }

    public function speedProfileDownload(){
        return $this->belongsTo(SpeedProfile::class, 'speed_download_id');
    }

    public function zone(){
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function ports(){
        return $this->belongsTo(OnuPort::class);
    }
}

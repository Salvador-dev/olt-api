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
        'cvlan_id',
        'tag_mode',
        'download_speed_id',
        'up_speed_id',
        'onu_id'
    ];

    public function onu()
    {
        return $this->belongsTo(Onu::class, 'onu_id');
    }

    public function download_speed()
    {
        return $this->belongsTo(SpeedProfile::class, 'download_speed_id');
    }

    public function up_speed()
    {
        return $this->belongsTo(SpeedProfile::class, 'up_speed_id');
    }
}

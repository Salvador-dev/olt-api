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
        'speed_profile_id',
        'onu_id'
    ];

    public function onu()
    {
        return $this->belongsTo(Onu::class, 'onu_id');
    }

    public function speed_profile()
    {
        return $this->belongsTo(SpeedProfile::class, 'id');
    }

 
}

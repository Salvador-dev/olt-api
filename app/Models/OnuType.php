<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnuType extends Model
{
    use HasFactory;
    protected $table = 'onu_types';
    protected $fillable = [
        'name',
        'pon_type_id',
        'capability_id',
        'ethernet_ports',
        'wifi_ports',
        'voip_ports',
        'catv',
        'allow_custom_profiles',
    ];

    public function capabilities(){
        return $this->hasMany(Capability::class, 'id');
    }

    public function pon_types(){
        return $this->hasMany(PonType::class, 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeedProfile extends Model
{
    use HasFactory;
    protected $table = 'speed_profiles';
    protected $fillable = [
        'name', 
        'use_prefix', 
        'preview_huawei', 
        'preview_zte', 
        'type', 
        'type_conexion', 
        'upload_speed',
        'download_speed',
    ];

    public function services_ports(){
        return $this->hasMany(ServicePort::class);
    }
}

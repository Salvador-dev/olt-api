<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeedProfile extends Model
{
    use HasFactory;
    protected $table ='speed_profiles';
    protected $primaryKey = 'idSpeedProfile';
    protected $fillable = [
      'name',
      'type_conexion',
      'type_speed',
      'prefix',
      'speed',
      'type',
      'is_default'
    ];

    public function onus(){
        return $this->hasMany(Onu::class, 'speed_up_id', 'speed_download_id');
    }
}

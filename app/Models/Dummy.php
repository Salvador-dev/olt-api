<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dummy extends Model
{
    use HasFactory;
    protected $table = 'dummy';
    protected $fillable = ['unique_external_id', 'SERIAL', 'olt_id', 'onu_type_id', 'zone_id', 'name', 'status'];

    public function service_ports()
    {
        return $this->hasMany(ServicePort::class, 'onu_id', 'id');
    }

    public function ethernet_ports()
    {
        return $this->hasMany(EthernetPort::class);
    }

    public function olt()
    {
        return $this->belongsTo(Olt::class);
    }

    // Filter scopes

    public function scopeName($query, $name){

        if($name){
            return $query->where('dummy.name', 'LIKE', "%$name%");
        }
    }

    public function scopeSn($query, $sn){

        if($sn){
            return $query->where('sn', 'LIKE', "%$sn%");
        }
    }

    public function scopeStatus($query, $status){

        if($status){
            return $query->where('status', $status);
        }
    }
    
    public function scopeCreatedAt($query, $date){

        if($date){
            return $query->where('created_at', '>=', now()->subDays($date));
        }
    }
}

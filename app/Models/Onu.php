<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onu extends Model
{
    use HasFactory;
    protected $table = 'onus';
    protected $fillable = [
        'unique_external_id',
        'pon_type_id',
        'serial',
        'olt_id',
        'board',
        'port',
        'onu_type_id',
        'zone_id',
        'name',
        'address',
        'odb_id',
        'mode',
        'wan_mode',
        'ip_address',
        'subnet_mask',
        'default_gateway',
        'dns1',
        'dns2',
        'username',
        'password',
        'catv',
        'administrative_status',
        'authorization_date',
        'status_id',
        'signal_id',
        'speed_profile_id', // Posiblemente sea a traves de service ports
        'latitude',
        'longitude',
        'wifi_port_id',
    ];

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

    // FILTER SCOPES

    public function scopeSearch($query, $search){

        if($search){
            return $query->where('onus.name', 'LIKE', "%$search%")->orWhere('onus.serial', 'LIKE', "%$search%");
        }
    }

    public function scopePort($query, $port){

        if($port){
            return $query->where('port', $port);
        }
    }

    public function scopeBoard($query, $board){

        if($board){
            return $query->where('board', $board);
        }
    }
    
    public function scopeCreatedAt($query, $date){

        if($date){
            return $query->where('created_at', '>=', now()->subDays($date));
        }
    }
}

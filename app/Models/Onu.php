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
        'status',
        'signal',
        'signal_1310',
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

    public function scopeStatus($query, $status){

        if($status){
            return $query->where('status', $status);
        }
    }

    public function scopeSignal($query, $signal){

        if($signal){
            return $query->where('signal', $signal);
        }
    }
    
    public function scopeCreatedAt($query, $date){

        if($date){
            return $query->where('created_at', '>=', now()->subDays($date));
        }
    }
}

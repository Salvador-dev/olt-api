<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EthernetPort extends Model
{
    use HasFactory;
    protected $table = 'ethernet_ports';
    protected $fillable = [
        'port',
        'admin_state',
        'mode',
        'dhcp',
        'service_port',
        'onu_id'
    ];

    public function onu()
    {
        return $this->belongsTo(Onu::class, 'onu_id');
    }
}

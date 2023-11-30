<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    use HasFactory;

    protected $fillable = ['hardware_version_id', 'client_id'];

    public function hardwareVersion()
    {
        return $this->belongsTo(HardwareVersion::class, 'hardware_version_id');
    }

    // public function cliente()
    // {
    //     return $this->belongsTo(Client::class, 'client_id');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonPort extends Model
{
    use HasFactory;
    protected $table = 'pon_ports';
    protected $fillable = [
        'pon_type_id',
        'admin_state',
        'onus',
        'average_signal',
        'description',
        'range',
        'tx_power',
        'olt_id',
    ];
}

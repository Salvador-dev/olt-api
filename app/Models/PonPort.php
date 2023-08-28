<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonPort extends Model
{
    use HasFactory;
    protected $table = 'pon_ports';
    protected $fillable = [
        'board',
        'pon_type_id',
        'admin_status',
        'onus',
        'average_signal',
        'description',
        'tx_power',
        'online_onus_count',
        'min_range',
        'max_range',
        'operational_status',
        'olt_id',
    ];
}

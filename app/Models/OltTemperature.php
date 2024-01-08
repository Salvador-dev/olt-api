<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OltTemperature extends Model
{
    use HasFactory;

    protected $table = 'olt_temperature';

    protected $fillable = [
        'olt_id',
        'uptime',
        'env_temp',
    ];

    public function olt()
    {
        return $this->belongsTo(Olt::class, 'olt_id');
    }
}

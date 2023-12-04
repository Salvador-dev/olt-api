<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oid extends Model
{
    use HasFactory;
    protected $fillable = ['hardware_version_id', 'oid', 'description'];

    public function hardwareVersion()
    {
        return $this->belongsTo(HardwareVersion::class, 'hardware_version_id');
    }
}

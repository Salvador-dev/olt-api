<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HardwareVersion extends Model
{
    use HasFactory;
    protected $table = 'hardware_versions';
    protected $fillable = [
        'name'
    ];
}

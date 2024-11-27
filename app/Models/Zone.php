<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    protected $table = 'zones';
    protected $fillable = [
        'name',
        'description',
        'smart_olt_id'
    ];
}

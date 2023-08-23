<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odb extends Model
{
    use HasFactory;
    protected $table = 'odbs';
    protected $fillable = [
        'name',
        'nr_of_ports',
        'latitude',
        'longitude',
        'zone_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OltCard extends Model
{
    use HasFactory;
    protected $table = 'olt_cards';
    protected $fillable = [
        'slot',
        'type',
        'real_type',
        'ports',
        'sw',
        'olt_id',
    ];
}

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
        'software_version',
        'olt_id',
        'status'
    ];

    public function olt(){
        return $this->belongsTo(Olt::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnuPort extends Model
{
    use HasFactory;
    protected $table = 'onu_ports';
    
    public function onu(){
        return $this->belongsTo(Onu::class, 'onu_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onu extends Model
{
    use HasFactory;
    protected $table = 'onus';

    public function olt()
    {
        return $this->belongsTo(Olt::class);
    }

    public function speedProfile(){
        return $this->belongsTo(SpeedProfile::class);
    }

    public function zone(){
        return $this->belongsTo(Zone::class);
    }
}

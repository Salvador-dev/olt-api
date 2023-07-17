<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Olt extends Model
{
    use HasFactory;
    protected $primaryKey = 'idOlt';
    protected $table = 'olts';

    public function onus()
    {
        return $this->hasMany(Onu::class, 'olt_id', 'idOlt');
    }
}

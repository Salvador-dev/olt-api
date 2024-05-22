<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = ['action', 'onu_id', 'user_id'];
    // TODO: CAMBIAR A TRUE LUEGO DE HACER PRUEBAS
    public $timestamps = true; 
    // public $timestamps = false;

    public function onu()
    {
        return $this->belongsTo(Onu::class, 'onu_id');
    }

}

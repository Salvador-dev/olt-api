<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    use HasFactory;
    protected $table = 'capabilitys';
    protected $fillable = [
        'name',
        'description',
    ];


    public function onu_type(){
        return $this->hasMany(OnuType::class);
    }
}

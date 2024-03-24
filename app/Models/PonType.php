<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonType extends Model
{
    use HasFactory;
    protected $table = 'pon_types';
    protected $fillable = [
        'name'
    ];

    public function onu_type(){
        return $this->hasMany(OnuType::class);
    }
}

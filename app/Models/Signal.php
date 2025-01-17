<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    use HasFactory;
    protected $table = 'signal';
    protected $fillable = ['description', 'max_frequency', 'signal_id'];
}

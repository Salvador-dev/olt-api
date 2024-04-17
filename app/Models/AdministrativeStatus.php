<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativeStatus extends Model
{
    use HasFactory;
    protected $table = 'administrative_status';
    protected $fillable = ['description', 'status_id'];
}

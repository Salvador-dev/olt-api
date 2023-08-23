<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemoteAcl extends Model
{
    use HasFactory;
    protected $table = 'remote_acls';
    protected $fillable = [
        'olt_id',
        'acces_list_1',
        'acces_list_2',
        'acces_list_3',
        'acces_list_4',
    ];
}

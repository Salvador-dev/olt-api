<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareVersion extends Model
{
    use HasFactory;
    protected $table = 'software_versions';
    protected $fillable = [
        'name',
    ];

    public function olts()
    {
        return $this->hasMany(Olt::class);
    }
}

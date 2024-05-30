<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;

    protected $table = 'diagnostics';
    protected $fillable = ['signal_value', 'distance', 'onu_id', 'status_id', 'signal_id'];
    public $timestamps = true; 

    public function onu()
    {
        return $this->belongsTo(Onu::class, 'onu_id');
    }
}

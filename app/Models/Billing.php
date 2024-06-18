<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'billings';
    protected $fillable = ['olt_id', 'price_currency', 'monthly_price', 'subscription_status', 'subscription_end_date'];
    public $timestamps = true; 

    public function olt()
    {
        return $this->belongsTo(Olt::class, 'olt_id');
    }
}

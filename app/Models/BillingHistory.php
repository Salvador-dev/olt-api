<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingHistory extends Model
{
    use HasFactory;
    
    protected $table = 'billing_history';
    protected $fillable = ['billing_id', 'transaction_id', 'user_id', 'months_paid'];
    public $timestamps = true; 

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

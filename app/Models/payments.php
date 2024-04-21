<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;
    public $fillable = [
        'amount',
        'date',
        'status',
        'test_id',
        'order_id',
        'payment_method'
        ];
}

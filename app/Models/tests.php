<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tests extends Model
{
    use HasFactory;
    public $fillable = [
        'appointment_id',
        'status',
        'test_type',
        'samples',
        'payment_status',
        'date_of_testing',
        ];
}

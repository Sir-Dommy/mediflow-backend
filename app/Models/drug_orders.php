<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class drug_orders extends Model
{
    use HasFactory;
    public $fillable = [
        'test_id',
        'drugs',
        'prescribed_by',
        ];
}

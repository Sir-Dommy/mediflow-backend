<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class flights extends Model
{
    use HasFactory;
    public $fillable = [
        'test_id',
        'status',
        'package',
        'date_of_flight',
        ];
}

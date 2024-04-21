<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class results extends Model
{
    use HasFactory;
    public $fillable = [
        'test_id',
        'results',
        'ai_response',
        'doctor_response',
        'added_by',
        'updated_by',
        ];
}

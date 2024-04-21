<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patients extends Model
{
    use HasFactory;
    
    public $fillable =[
        'name',
        'gender',
        'phone',
        'email',
        'county',
        'sub_county',
        'district',
        'location',
        'dob'
        ];
}

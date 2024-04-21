<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointments extends Model
{
    use HasFactory;
    public $fillable = [
        'patient_id',
        'symptoms',
        'medicated',
        'medications',
        'doctor_id',
        'appointment_date',
        ];
}

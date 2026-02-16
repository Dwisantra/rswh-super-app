<?php

namespace Modules\RegOnline\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'clinic_name',
        'doctor_name',
        'schedule_at',
        'complaint',
        'queue_number',
        'status',
        'simrs_response',
    ];

    protected function casts(): array
    {
        return [
            'schedule_at' => 'datetime',
            'simrs_response' => 'array',
        ];
    }
}

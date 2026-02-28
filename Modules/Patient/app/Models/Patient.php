<?php

namespace Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Patient\Database\Factories\PatientFactory;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'norm', 'nik', 'kap', 'name', 'panggilan', 'gelar_depan', 
        'gelar_belakang', 'birth_date', 'birth_place', 'gender', 
        'religion', 'contact', 'address', 'occupation', 'marital_status', 
        'relation', 'is_default'
    ];

    // protected static function newFactory(): PatientFactory
    // {
    //     // return PatientFactory::new();
    // }
}

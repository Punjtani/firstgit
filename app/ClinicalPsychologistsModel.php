<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicalPsychologistsModel extends Model
{
    //
    public $table = 'clinical_psychologists';
    public $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'name',
        'email',
        'phone',
        'age',
        'cnic',
        'qualification',
        'experience',
        'specialty',
        'bank_name',
        'account_number',
        'bank_slip',
        'cv',
        'docs'
    ];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{
    use SoftDeletes;
    protected $fillable = ['qty', 'price', 'quotation_id', 'medical_service_id', 'tooth_no', '_who_added'];

    public function medical_service()
    {
        return $this->belongsTo('App\MedicalService', 'medical_service_id');
    }


    public function addedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }
}

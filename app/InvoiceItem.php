<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes;
    protected $fillable = ['qty', 'price', 'invoice_id', 'medical_service_id', 'tooth_no', 'doctor_id', '_who_added'];

    public function medical_service()
    {
        return $this->belongsTo('App\MedicalService', 'medical_service_id');
    }

    public function procedure_doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

}

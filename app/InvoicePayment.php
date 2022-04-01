<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePayment extends Model
{
    use SoftDeletes;
    protected $fillable = ['amount', 'payment_method', 'account_name', 'cheque_no', 'bank_name',
        'payment_date', 'invoice_id', 'insurance_company_id', 'self_account_id', 'branch_id', '_who_added'];

    public function addedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpensePayment extends Model
{
    use SoftDeletes;
    protected $fillable = ['payment_date', 'amount', 'payment_method', 'payment_account_id', 'expense_id', '_who_added'];

    public function paymentAccount()
    {
        return $this->belongsTo('App\ChartOfAccountItem', 'payment_account_id');
    }

    public function AddedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }
}

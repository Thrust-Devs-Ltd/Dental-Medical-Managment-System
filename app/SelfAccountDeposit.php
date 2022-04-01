<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelfAccountDeposit extends Model
{
    use SoftDeletes;
    protected $fillable = ['amount', 'payment_method', 'payment_date', 'self_account_id', '_who_added'];
}

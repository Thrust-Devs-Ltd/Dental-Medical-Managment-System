<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsTransaction extends Model
{
    protected $fillable = ['amount', 'type', '_who_added'];
}

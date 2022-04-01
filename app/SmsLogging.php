<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsLogging extends Model
{
    protected $fillable = ['phone_number', 'message','type', 'cost', 'status'];
}

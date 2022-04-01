<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookAppointment extends Model
{
    protected  $fillable=['full_name','phone_number','email','message','status'];
}

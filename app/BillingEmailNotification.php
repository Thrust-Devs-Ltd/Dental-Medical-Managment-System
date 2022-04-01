<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingEmailNotification extends Model
{
    use SoftDeletes;
    protected $fillable=['email','message','item_id','notification_type','status','_who_added'];
}

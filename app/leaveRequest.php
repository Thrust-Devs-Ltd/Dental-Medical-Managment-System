<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class leaveRequest extends Model
{
    use  SoftDeletes;
    protected  $fillable=['leave_type_id','start_date','duration','status','action_date','_who_added','_approved_by'];
}

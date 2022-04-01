<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BirthDayMessage extends Model
{
    use SoftDeletes;
    protected $fillable=['message','_who_added'];
}

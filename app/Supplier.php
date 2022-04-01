<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', '_who_added'];

    public function addedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }
}

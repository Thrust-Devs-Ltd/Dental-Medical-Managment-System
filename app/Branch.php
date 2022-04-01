<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'is_active', '_who_added'];

    public function addedBy()
    {
        return $this->belongsTo('App\User', '_who_added');
    }
}

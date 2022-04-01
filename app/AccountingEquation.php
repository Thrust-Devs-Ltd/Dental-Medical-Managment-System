<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountingEquation extends Model
{
    public function Categories()
    {
        return $this->hasMany('App\ChartOfAccountCategory');
    }
}

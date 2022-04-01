<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccountCategory extends Model
{
    protected $fillable = ['name', 'description', 'accounting_equation_id', '_who_added'];

    public function Items()
    {
        return $this->hasMany('App\ChartOfAccountItem');
    }
}

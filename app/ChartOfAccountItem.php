<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccountItem extends Model
{
    protected $fillable = ['name', 'description', 'chart_of_account_category_id', '_who_added'];
}

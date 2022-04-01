<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelfAccount extends Model
{
    use SoftDeletes;
    protected $fillable = ['account_no', 'account_holder', 'holder_phone_no', 'holder_email', 'holder_address', 'is_active', '_who_added'];

    public static function AccountNo()
    {
        $latest = self::latest()->first();
        if (!$latest) {
            return date('Y') . "" . '0001';
        } else if ($latest->deleted_at != "null") {
            //now use the time stamp
            return time() + $latest->id + 1;
        } else {
            return date('Y') . "" . sprintf('%04d', $latest->id + 1);
        }
    }

}

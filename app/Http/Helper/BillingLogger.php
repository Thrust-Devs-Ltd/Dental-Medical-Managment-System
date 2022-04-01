<?php


namespace App\Http\Helper;


use App\BillingEmailNotification;
use Illuminate\Support\Facades\Auth;

class BillingLogger
{


    public static function LogNotification($email,$message, $item_id, $notification_type, $status)
    {
        BillingEmailNotification::create([
            'email'=>$email,
            'message' => $message,
            'item_id' => $item_id,
            'notification_type' => $notification_type,
            'status' => $status,
            '_who_added' => Auth::User()->id
        ]);
    }
}

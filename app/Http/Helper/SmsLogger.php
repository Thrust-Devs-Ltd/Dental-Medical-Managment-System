<?php


namespace App\Http\Helper;


use AfricasTalking\SDK\AfricasTalking;
use App\SmsLogging;
use App\SmsTransaction;

class SmsLogger
{

    protected $username;
    protected $apiKey;

    public function __construct()
    {
        $this->username=env('AfricasTalking_Username', null); // use 'sandbox' for development in the test environment
        $this->apiKey = env('AfricasTalking_Api_Key', null);; // use your sandbox app API key for development in the test environment


    }

    public function SendMessage($phone_number, $message, $type)
    {
        $AT = new AfricasTalking($this->username, $this->apiKey);
        // Get one of the services
        $sms = $AT->sms();
//        now send out the message
        $success = $sms->send([
            'to' => $phone_number,
            'message' => $message,
        ]);
        if ($success) {
            //sms logging
            $this->LogSms($phone_number, $message, $type);
        }
    }

    private function LogSms($phone_number, $message, $type)
    {
        $success = SmsLogging::create([
            'phone_number' => $phone_number,
            'message' => $message,
            'cost' => '50',
            'type' => $type,
            'status' => 'success'
        ]);
        if ($success) {
            //log the transaction
            FunctionsHelper::TrackAfricaIsTalkingTransactions("50", "sms", null);
        }
    }

}

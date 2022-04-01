<?php

namespace App\Jobs;

use App\Http\Helper\SmsLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAppointmentSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $phone_no;
    protected $msg;
    protected $sms_logger;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @param $phone_number
     * @param $message
     */
    public function __construct($phone_number, $message,$type)
    {
        $this->phone_no = $phone_number;
        $this->msg = $message;
        $this->type=$type;
        $this->sms_logger = new SmsLogger();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sms_logger->SendMessage($this->phone_no, $this->msg,$this->type);
    }


}

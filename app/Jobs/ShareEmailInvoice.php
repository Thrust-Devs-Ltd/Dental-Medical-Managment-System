<?php

namespace App\Jobs;

use App\BillingEmailNotification;
use App\Http\Helper\BillingLogger;
use App\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use PDF;

class ShareEmailInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $message;
    protected $status;

    /**
     * Create a new job instance.
     *
     * @param $data
     * @param $email
     * @param $message
     */
    public function __construct($data, $email, $message)
    {
        $this->data = $data;
        $this->email = $email;
        $this->message = $message;
        $this->status = "failed";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $pdf = PDF::loadView('invoices.receipt_print', $this->data)->setPaper('a4');

        $email_to = $this->email;
        if ($this->message == null) {
            $this->message = 'Thank for letting us serve you at '.env('CompanyName',null).', Please find the attached copy of the Invoice;';
        }

        $other_data['user_info'] = array(
            'surname' => $this->data['patient']->surname,
            'othername' => $this->data['patient']->othername,
            'email' => $this->email,
            'phone_number' => $this->data['patient']->phone_no,
            'message' => $this->message
        );

        Mail::send('emails.invoice', $other_data, function ($message) use ($pdf, $email_to) {
            $message->from(env('CompanyNoReplyEmail',null));
            $message->to($email_to);
            $message->subject(env('CompanyName',null).' Invoice');
            $message->attachData($pdf->output(), date("Y-m-d H:i:s") . 'invoice.pdf');
        });


        if (!Mail::failures()) {
            $this->status = "sent";
        }

        BillingLogger::LogNotification($email_to, $this->message, $this->data['invoice']->id, "Invoice", $this->status);
    }
}

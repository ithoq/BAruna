<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;
use Illuminate\Mail\Message;

class SendgridTestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendgrid:testmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sendgrid Test Mail';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //Begin Send Mail
                $invMail = array(
                                    'invoice_number' => '11',
                                    'total' => 'Rp.',
                                    'duedate'  => '$invoice->duedate',
                                    'issued_date' => '$invoice->created_at->toDateString()',
                                    'to_email' => 'wisnu@guestpro.id',
                                    'to_name'  => '$company_data->name',
                                    'from_email' => env('SENDGRID_FROM_EMAIL'),
                                    'from_name' => env('SENDGRID_FROM_NAME'),
                                    'replyto_email' => env('SENDGRID_REPLYTO'),
                                    'replyto_name' => env('SENDGRID_REPLYTO_NAME'),
                                    'subject' => '[GuestPro] Customer Invoice',
                                    'sender_signature_name' => env('SENDGRID_SIGNATURE_NAME'),
                                    'sender_signature_job' => env('SENDGRID_SIGNATURE_JOB'),
                                    'sender_address'  => env('SENDGRID_SENDER_ADDRESS'),
                                    'sender_city' => env('SENDGRID_SENDER_CITY'),
                                    'sender_state'  => env('SENDGRID_SENDER_STATE'),
                                    'sender_cuntry' => env('SENDGRID_SENDER_CUNTRY'),
                                    'sender_zip'  => env('SENDGRID_SENDER_ZIP'),
                                    'web_link'    => env('SENDGRID_WEB_LINK').'$company_data->id$invoice->id',
                                    'body'  => '[GuestPro] Customer Invoice',
                                    'template_id' => env('SENDGRID_TEMPLATE_1'),
                                );

                $send_00 = false;

                //sending email

                try {

                  $response = Mail::send('email.plain', ['data' => $invMail['body']], function (Message $message) use ($invMail) {
                    $message->to($invMail['to_email'], $invMail['to_name']);
                    $message->from($invMail['from_email'], $invMail['from_name']);
                    $message->setReplyTo($invMail['replyto_email'], $invMail['replyto_name']);
                    $message->subject($invMail['subject']);
                    $message->embedData([
                          'personalizations' => [
                              [
                                  'to' => [
                                      'email' => $invMail['to_email'],
                                      'name' => $invMail['to_name'],
                                  ],
                                  'substitutions' => [
                                      '%pms_client_name%'     => $invMail['to_name'],
                                      '%invoice_number%'      => '#'.$invMail['invoice_number'],
                                      '%invoice_issued_date%' => $invMail['issued_date'],
                                      '%invoice_amount_due%'  => $invMail['total'],
                                      '%invoice_due_date%'    => $invMail['duedate'],
                                      '%invoice_sender_name%' => $invMail['from_name'],
                                      '%invoice_sender_address%' => $invMail['sender_address'],
                                      '%invoice_sender_city%'    => $invMail['sender_city'],
                                      '%invoice_sender_state%'   => $invMail['sender_state'],
                                      '%invoice_sender_cuntry%'  => $invMail['sender_cuntry'],
                                      '%invoice_sender_zip%'     => $invMail['sender_zip'],
                                      '%invoice_sender_signature_name%' => $invMail['sender_signature_name'],
                                      '%invoice_sender_signature_job%'  => $invMail['sender_signature_job'],
                                      '%invoice_weblink%'     => $invMail['web_link'],
                                  ],
                              ],
                          ],
                          'categories' => ['guestpro_invoice'],
                          'template_id' => $invMail['template_id'],
                          'custom_args' => [
                              'user_id' => '123'
                          ]
                      ], 'sendgrid/x-smtpapi');
                  });

                  if($response->getStatusCode()=='202' || $response->getStatusCode()=='200'){
                    $send_00 = true;
                  }

                } catch (ModelNotFoundException $e) {

                  $send_00 = false;

                }
        //end of sending email

    }
}

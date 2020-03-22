<?php

namespace NeubusSrm\Jobs;

use Aws\Ses\SesClient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use NeubusSrm\Mail\TempPassword;

class SendTempPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $subject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $password, string $email, string $message, string $subject)
    {
        $this->password = $password;
        $this->email = $email;
        $this->message = $message;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $client = new SesClient(config('aws'));
            $client->sendEmail([
                'Source'   => config('mail.from.address'),
                'Message' => ['Subject' => ['Data' => $this->subject], 'Body' => ['Text' => ['Charset' => "UTF-8",
                    'Data' => $this->message]]],
                'Destination' => ['ToAddresses' => [$this->email]]]);
        }
        catch (\Exception $e){
            Log::info('Email failed for temp password: ' . $e->getMessage());
        }
        Log::info('email sent ' . $this->email . ' ' . $this->password);
    }
}

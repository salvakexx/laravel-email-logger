<?php

namespace Salvakexx\EmailLogger\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobErrorEmailLogger implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct(Request $request,\Exception $exception,$message = false,$user = false)
    {
        $this->data = \EmailLogger::errorData($exception,$request,$message,$user);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \EmailLogger::sendEmail('email-logger::mail.error',$this->data);
    }
}

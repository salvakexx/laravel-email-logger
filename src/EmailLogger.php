<?php

namespace Salvakexx\EmailLogger;


class EmailLogger
{
    protected $emails = [];
    /**
     * EmailLogger constructor.
     */
    public function __construct()
    {
        $this->emails = config('email-logger.emails')?:[];
    }

    public function info($request, $message = false)
    {

    }

    public function error($exception, $request, $message = false)
    {

    }

    protected function sendEmail($view,$data)
    {

    }
}
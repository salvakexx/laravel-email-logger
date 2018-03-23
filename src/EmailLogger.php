<?php

namespace Salvakexx\EmailLogger;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use League\Flysystem\Exception;

class EmailLogger
{
    protected $emails = [];
    /**
     * EmailLogger constructor.
     */
    public function __construct()
    {
        $this->setEmails(config('email-logger.emails')?:[]);
    }

    public function info($request, $message = false)
    {
        $data = array_merge(
            $this->getBaseData(),
            $this->getRequestData($request),[
            'message' => $this->prepareMessage($message),
        ]);

        $this->sendEmail('email-logger::mail.info',$data);
    }

    public function error($exception, $request, $message = false)
    {
        $data = array_merge(
            $this->getBaseData(),
            $this->getRequestData($request),
            $this->getExceptionData($exception),[
            'message' => $this->prepareMessage($message),
        ]);

        $this->sendEmail('email-logger::mail.error',$data);
    }

    protected function prepareMessage($message)
    {
        try{
            $return = is_string($message) ? $message : print_r($message,1);
        } catch (Exception $exception){
            $return = 'Cannot print this message sorry '.get_class($message);
        }
        return $return;
    }
    protected function getRequestData(Request $request)
    {
        return [
            'requestUrl' => url($request->getPathInfo()),
            'requestParameters' => print_r($request->all(),1),
        ];
    }
    protected function getExceptionData(Exception $exception)
    {
        return [
            'exceptionCode' => $exception->getCode(),
            'exceptionClass' => get_class($exception),
            'exceptionMessage' => $exception->getMessage(),
            'exceptionFile' => $exception->getFile(),
            'exceptionLine' => $exception->getLine(),
            'exceptionTrace' => print_r($exception->getTrace(),1),
        ];
    }
    protected function getBaseData()
    {
        return [
            'date' => date('H:i:s d-m-Y'),
        ];
    }

    protected function sendEmail($view,$data)
    {
        if(empty($emails)){
            return false;
        }

        try{
            Mail::send($view, $data, function($message)
            {
                $emails = $this->getEmails();
                $message->from(config('email-logger.from'),config('email-logger.from_name'));
                $message->to(array_first($emails));
                if(count($emails) > 1){
                    array_shift($emails);
                    $message->cc($emails);
                }
                $message->subject(config('email-logger.subject'));
            });

        }catch (Exception $exception){
            \Log::error('Email message were not send. Trouble with your driver');
            \Log::error(implode(' ',[
                get_class($exception),
                $exception->getCode(),
                $exception->getLine(),
                $exception->getMessage(),
                $exception->getTraceAsString()
            ]));
            return false;
        }

        return true;
    }


    public function getEmails()
    {
        return $this->emails;
    }

    public function setEmails($arrayEmails = [])
    {
        $this->emails = $arrayEmails;
    }
}
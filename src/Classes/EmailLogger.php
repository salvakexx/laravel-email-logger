<?php

namespace Salvakexx\EmailLogger;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

class EmailLogger
{
    protected $emails = [];
    /**
     * EmailLogger constructor.
     */
    public function __construct()
    {
        $this->setDefaultEmails();
    }

    public function emails($emails)
    {
        $this->setEmails($emails);
        return $this;
    }

    protected function setDefaultEmails()
    {
        $this->setEmails(config('email-logger.emails')?:[]);
        return $this;
    }

    public function info($request, $message = false, $user = false)
    {
        $data = array_merge(
            $this->getBaseData(),
            $this->getRequestData($request),
            $this->getUserData($user),[
            'messageLog' => $this->prepareMessage($message),
        ]);

        $this->sendEmail('email-logger::mail.info',$data, 'info');
    }

    public function error($exception, $request, $message = false, $user = false)
    {
        $this->sendEmail('email-logger::mail.error',$this->errorData($exception,$request,$message,$user));
    }

    public function errorData($exception, $request, $message = false, $user = false)
    {
        return array_merge(
            $this->getBaseData(),
            $this->getRequestData($request),
            $this->getExceptionData($exception),
            $this->getUserData($user),[
            'messageLog' => $this->prepareMessage($message),
        ]);
    }

    protected function prepareMessage($message)
    {
        try{
            $return = is_string($message) ? $message : print_r($message,1);
        } catch (\Exception $exception){
            $return = 'Cannot print this message sorry '.get_class($exception);
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
    protected function getExceptionData(\Exception $exception)
    {
        return [
            'exceptionCode' => $exception->getCode(),
            'exceptionClass' => get_class($exception),
            'exceptionMessage' => $exception->getMessage(),
            'exceptionFile' => $exception->getFile(),
            'exceptionLine' => $exception->getLine(),
            'exceptionTrace' => $exception->getTraceAsString(),
        ];
    }
    protected function getUserData($user = false)
    {
        $return = ['user'=>false];
        if($user instanceof Model){
            $return['user'] = print_r($user->toArray(),1);
        }elseif (is_array($user)){
            $return['user'] = print_r($user,1);
        }
        return $return;
    }
    protected function getBaseData()
    {
        return [
            'date' => date('H:i:s d-m-Y'),
        ];
    }

    public function sendEmail($view,$data,$logType = 'error')
    {
        if(empty($this->getEmails())){
            return false;
        }
        try{
            Mail::send($view, $data, function($message) use ($logType)
            {
                $emails = $this->getEmails();
                $message->from(config('email-logger.from'),config('email-logger.from_name'));
                $message->to(array_first($emails));
                if(count($emails) > 1){
                    array_shift($emails);
                    $message->cc($emails);
                }
                $message->subject('['.$logType.'] '.config('email-logger.subject'));
            });
//        }catch (\Swift_TransportException $exception){
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

        $this->setDefaultEmails();

        return true;
    }

    public function getViewMailable($view,$data)
    {
        return (new MailMessage)
            ->view($view,$data);
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
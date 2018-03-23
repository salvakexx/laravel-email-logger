<?php

namespace Salvakexx\EmailLogger\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
//use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificateErrorEmailLogger extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;

    protected $exception;

    protected $message;

    /**
     * NotificateAboutRoomReserving constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request,\Exception $exception,$message = false)
    {
        $this->request   = $request;
        $this->exception = $exception;
        $this->message   = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  User $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        \EmailLogger::error($this->request,$this->exception,$this->message);
    }
}

<?php

namespace Salvakexx\EmailLogger;


use Illuminate\Support\Facades\Facade;

class EmailLoggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'email-logger';
    }
}
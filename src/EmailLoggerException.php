<?php
/**
 * Created by PhpStorm.
 * User: kexx
 * Date: 3/23/18
 * Time: 10:53 AM
 */

namespace Salvakexx\EmailLogger;


use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailLoggerException extends HttpException
{
    //we cannot use exceptions cause we are handling them bro...
}
# laravel-email-logger
Simple Laravel package for logging via email

## Installation

Add the package using composer:

```shell
composer require salvakexx/laravel-email-logger
```

In your config/app.php :
```php
'providers' => [
  /*
   * Package Service Providers...
   */
   Salvakexx\EmailLogger\EmailLoggerServiceProvider::class,
],
```


```php
'aliases' => [
  /*
   * Class Aliases...
   */
   'EmailLogger' => \Salvakexx\EmailLogger\EmailLoggerFacade::class,
],
```

Publish the configuration file

```shell
php artisan vendor:publish --provider="Salvakexx\EmailLogger\EmailLoggerServiceProvider"
```



## Quickstart

1. Fill emails (and other parameters) in app/config/email-logger.php
```php
    'emails' => [
        //fill this array with emails that will receive logs
    ],
//    'emails'    => explode(',',env('MAIL_LOGGER_EMAILS')),
```

2. Check out your email configuration. Mail Facade must be working

## Using

```php
  \EmailLogger::info(request(),'Information on action etc. ');
  
  \EmailLogger::error($exception,request(),'Error happened please check');
```
  
You can catch every exception directly, or catch all exceptions on your website.
To do this override render() function in app/Exceptions/Handler.php for example :

```php
    
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //exclude the common exceptions
        $exception = $this->prepareException($exception);
        if(
            !$exception instanceof NotFoundHttpException
            && !$exception instanceof AuthenticationException
            && !$exception instanceof ValidationException
        ){
            \EmailLogger::error($exception,$request,'Internal Server Error happened');
        }
        
        //Track user camed to 404
        if($exception instanceof NotFoundHttpException){
            \EmailLogger::info($request,'User lost somehow check please');
        }

        return parent::render($request, $exception);
    }

```


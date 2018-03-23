@component('mail::message')

<p>
    Hi ! There is a new ERROR log for mailing :
</p>
<p>Date : {{$date}}</p>
@if($message)
    <h4>
        Message :
    </h4>
    <p>
        {{$message}}
    </p>
@endif

@include('email-logger::mail.error_block')
@include('email-logger::mail.request_block')

@endcomponent

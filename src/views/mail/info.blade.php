@component('mail::message')

<p>
    Hi ! There is a new info log for mailing :
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

@include('email-logger::mail.request_block')

@endcomponent

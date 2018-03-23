<p>
    Hi ! There is a new ERROR log for mailing :
</p>
<p>Date : {{$date}}</p>
@if($messageLog)
<h4>
Message :
</h4>
<p>
{{$messageLog}}
</p>
@endif

@include('email-logger::mail.request_block')
@include('email-logger::mail.error_block')

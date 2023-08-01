@component('mail::message')
# List of Shorlisted Applicants

Dear <b>{{ $data['name'] }}</b><br>

Item Number : <b>{{ $data['itemnum'] }}</b><br/>
Position : <b>{{ $data['position'] }}</b><br/>
Division : <b>{{ $data['division'] }}</b><br/>
Deadline : <b>{{ $data['deadline'] }}</b><br/>
<br>
Here are the list of applicants <a href="{{ $data['link'] }}" target="_blank">click here</a>


Thanks,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# REGRET LETTER

Good morning/afternoon. 

Relative to your application to the <b>{{ $data['position'] }}</b> position of the <b>{{ $data['division']  }}</b>, our service provider will contact you about the details/arrangements for the conduct of the pre-employment psychological exam.

Thank you.


Thank You,<br>
{{ config('app.name') }}
@endcomponent

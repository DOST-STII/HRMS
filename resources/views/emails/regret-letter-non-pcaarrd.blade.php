@component('mail::message')
# REGRET LETTER

Thank you for your interest in applying for the <b>{{ $data['position'] }}</b> post of the <b>{{ $data['division']  }}</b> Division. However, we regret to inform you that a more qualified applicant have been shortlisted to the position.


Thanks,<br>
{{ config('app.name') }}
@endcomponent

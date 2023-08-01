@component('mail::message')
# REGRET LETTER

Thank you for your interest to apply to the <b>{{ $data['position'] }}</b> position of <b>{{ $data['division']  }}</b>. We regret to inform you that you did not meet the minimum qualifications of the position particularly the (Training required/work experience/eligibility/education).


Thank You,<br>
{{ config('app.name') }}
@endcomponent

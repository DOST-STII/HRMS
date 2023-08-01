@component('mail::message')
# REGRET LETTER

Thank you for your interest in applying for the position of <b>{{ $data['position'] }}</b> of <b>{{ $data['division']  }}</b> division. We would like to inform you that the Personnel Selection Board has completed the review and evaluation of candidates. After a careful and thorough selection process, we regret to inform you that a more qualified applicant has been favorably considered for the position.

Again, thank you and we hope that you continue to provide the same commitment in performing your current duties and responsibilities at PCAARRD.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

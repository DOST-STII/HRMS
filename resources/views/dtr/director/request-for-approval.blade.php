@if(Auth::user()->usertype == 'Marshal')
     @include('dtr.director.request-for-approval-oed')
@elseif(Auth::user()->usertype == 'Administrator')
     @include('dtr.director.request-for-approval-admin')
@else
     @include('dtr.director.request-for-approval-division')
@endif
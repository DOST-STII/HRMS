@if(Auth::user()->usertype == 'Marshal')
  @include('pis/staff/index-marshal')
@else
  @if(Auth::user()->employment_id == 1 || Auth::user()->employment_id == 13 || Auth::user()->employment_id == 14 || Auth::user()->employment_id == 15)
    @include('pis/staff/index-staff')
  @else
    @include('pis/staff/index-icos')
  @endif
@endif
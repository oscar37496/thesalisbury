@include('account.helpers.sidebar')
@include('account.helpers.content-header')
@include('account.helpers.title')
@include('account.helpers.account-dropdown')
@include('account.helpers.notifications')

@section('ga-parameters')
@if(isset($user)), 'userId': '{{{ $user->id }}}' @endif
@stop